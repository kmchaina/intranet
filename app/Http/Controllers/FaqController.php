<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FaqController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = $request->get('search');
        $category = $request->get('category');

        // Get featured FAQs
        $featured = Faq::published()
            ->featured()
            ->limit(3)
            ->get();

        // Get main FAQ results
        $faqs = Faq::search($query, $category)
            ->with(['creator'])
            ->paginate(20);

        // Get popular FAQs for sidebar
        $popular = Faq::published()
            ->popular(5)
            ->get();

        $categories = Faq::getCategories();

        return view('faqs.index', compact('faqs', 'featured', 'popular', 'categories', 'query', 'category'));
    }

    public function show(Faq $faq)
    {
        // Only show published FAQs to regular users
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $canManage = $user && $user->canManageFaqs();

        if ($faq->status !== 'published' && !$canManage) {
            abort(404);
        }

        // Increment view count
        $faq->incrementViews();

        // Get related FAQs
        $relatedFaqs = Faq::published()
            ->where('category', $faq->category)
            ->where('id', '!=', $faq->id)
            ->limit(5)
            ->get();

        return view('faqs.show', compact('faq', 'relatedFaqs'));
    }

    public function create()
    {
        $this->authorize('create', Faq::class);

        $categories = Faq::getCategories();

        return view('faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Faq::class);

        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|in:' . implode(',', array_keys(Faq::getCategories())),
            'keywords' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published',
            'order_position' => 'nullable|integer|min:0'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $faq = new Faq($request->all());
        $faq->created_by = $user->id;
        $faq->updated_by = $user->id;
        $faq->save();

        return redirect()->route('faqs.show', $faq)
            ->with('success', 'FAQ created successfully!');
    }

    public function edit(Faq $faq)
    {
        $this->authorize('update', $faq);

        $categories = Faq::getCategories();

        return view('faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $this->authorize('update', $faq);

        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|in:' . implode(',', array_keys(Faq::getCategories())),
            'keywords' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'order_position' => 'nullable|integer|min:0'
        ]);

        $faq->fill($request->all());
        $faq->updated_by = Auth::id();
        $faq->save();

        return redirect()->route('faqs.show', $faq)
            ->with('success', 'FAQ updated successfully!');
    }

    public function destroy(Faq $faq)
    {
        $this->authorize('delete', $faq);

        $faq->delete();

        return redirect()->route('faqs.index')
            ->with('success', 'FAQ deleted successfully!');
    }

    public function helpful(Faq $faq)
    {
        $faq->markHelpful();

        return response()->json([
            'success' => true,
            'helpful_count' => $faq->helpful_count,
            'percentage' => $faq->getHelpfulPercentage()
        ]);
    }

    public function notHelpful(Faq $faq)
    {
        $faq->markNotHelpful();

        return response()->json([
            'success' => true,
            'not_helpful_count' => $faq->not_helpful_count,
            'percentage' => $faq->getHelpfulPercentage()
        ]);
    }

    // FAQ Suggestion methods
    public function suggest()
    {
        $categories = Faq::getCategories();

        return view('faqs.suggest', compact('categories'));
    }

    public function storeSuggestion(Request $request)
    {
        $request->validate([
            'suggested_question' => 'required|string|max:255',
            'context' => 'nullable|string|max:1000',
            'category' => 'required|in:' . implode(',', array_keys(Faq::getCategories())),
        ]);

        FaqSuggestion::create([
            'suggested_question' => $request->suggested_question,
            'context' => $request->context,
            'category' => $request->category,
            'suggested_by' => Auth::id()
        ]);

        return redirect()->route('faqs.index')
            ->with('success', 'Thank you for your suggestion! It will be reviewed by our team.');
    }

    public function suggestions()
    {
        $this->authorize('manageFaqs');

        $suggestions = FaqSuggestion::with(['suggester', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('faqs.suggestions', compact('suggestions'));
    }

    public function reviewSuggestion(Request $request, FaqSuggestion $suggestion)
    {
        $this->authorize('manageFaqs');

        $request->validate([
            'action' => 'required|in:approve,reject,convert',
            'admin_notes' => 'nullable|string',
            'faq_question' => 'required_if:action,convert|string',
            'faq_answer' => 'required_if:action,convert|string',
        ]);

        $reviewerId = Auth::id();

        switch ($request->action) {
            case 'approve':
                $suggestion->approve($reviewerId, $request->admin_notes);
                $message = 'Suggestion approved successfully!';
                break;

            case 'reject':
                $suggestion->reject($reviewerId, $request->admin_notes);
                $message = 'Suggestion rejected.';
                break;

            case 'convert':
                // Create new FAQ from suggestion
                $faq = Faq::create([
                    'question' => $request->faq_question,
                    'answer' => $request->faq_answer,
                    'category' => $suggestion->category,
                    'status' => 'published',
                    'created_by' => $reviewerId,
                    'updated_by' => $reviewerId,
                ]);

                $suggestion->convertToFaq($faq->id, $reviewerId);
                $message = 'Suggestion converted to FAQ successfully!';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}

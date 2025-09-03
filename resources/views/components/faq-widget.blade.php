<!-- FAQ Widget Component -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Frequently Asked Questions</h3>
        <a href="{{ route('faqs.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
            View All
        </a>
    </div>

    @if ($featuredFaqs->isNotEmpty())
        <div class="space-y-3">
            @foreach ($featuredFaqs as $faq)
                <div class="border-l-2 border-blue-500 pl-3">
                    <a href="{{ route('faqs.show', $faq) }}"
                        class="block hover:bg-gray-50 -mx-3 px-3 py-2 rounded transition-colors">
                        <h4 class="font-medium text-gray-900 text-sm">{{ $faq->question }}</h4>
                        <div class="flex items-center text-xs text-gray-500 mt-1 space-x-3">
                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                {{ $faq->category_display }}
                            </span>
                            <span>{{ $faq->view_count }} views</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-6">
            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-gray-500 mt-2">No featured FAQs available</p>
        </div>
    @endif

    <div class="mt-4 pt-4 border-t border-gray-200">
        <a href="{{ route('faqs.suggest') }}"
            class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Suggest a Question
        </a>
    </div>
</div>

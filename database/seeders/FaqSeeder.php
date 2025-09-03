<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faq;
use App\Models\User;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        $faqs = [
            [
                'question' => 'How do I reset my password?',
                'answer' => 'To reset your password, click on the "Forgot Password" link on the login page, enter your email address, and follow the instructions sent to your email. You can also contact IT support for assistance.',
                'category' => 'it',
                'status' => 'published',
                'keywords' => 'password, reset, login, forgot, account',
                'is_featured' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ],
            [
                'question' => 'What are our working hours?',
                'answer' => 'Our standard working hours are Monday to Friday, 9:00 AM to 5:00 PM. However, flexible working arrangements may be available depending on your role and department. Please check with your supervisor for specific arrangements.',
                'category' => 'hr',
                'status' => 'published',
                'keywords' => 'working hours, schedule, time, office hours, flexible',
                'is_featured' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ],
            [
                'question' => 'How do I submit an expense report?',
                'answer' => 'You can submit expense reports through our finance portal. Log in with your credentials, navigate to "Expense Reports", fill out the form, and upload your receipts. Reports are typically processed within 5-7 business days.',
                'category' => 'finance',
                'status' => 'published',
                'keywords' => 'expense, report, receipts, finance, reimbursement, claims',
                'is_featured' => true,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ],
            [
                'question' => 'Where can I find the employee handbook?',
                'answer' => 'The employee handbook is available in the Documents section of the intranet. You can also request a physical copy from HR. The handbook contains important policies, procedures, and benefits information.',
                'category' => 'hr',
                'status' => 'published',
                'keywords' => 'handbook, policies, procedures, documents, HR',
                'is_featured' => false,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ],
            [
                'question' => 'How do I request time off?',
                'answer' => 'Time off requests should be submitted through the HR portal at least 2 weeks in advance. For emergency leave, contact your supervisor immediately and submit the request as soon as possible.',
                'category' => 'hr',
                'status' => 'published',
                'keywords' => 'time off, leave, vacation, sick leave, PTO',
                'is_featured' => false,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ],
            [
                'question' => 'Who do I contact for IT support?',
                'answer' => 'For IT support, you can email support@company.com or call the IT help desk at extension 1234. For urgent issues outside business hours, use the emergency IT hotline provided in your employee handbook.',
                'category' => 'it',
                'status' => 'published',
                'keywords' => 'IT support, help desk, technical issues, computer problems',
                'is_featured' => false,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::create($faqData);
        }

        $this->command->info('FAQ seeder completed successfully!');
    }
}

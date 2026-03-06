<?php

namespace App\Console\Commands;

use App\Mail\WeeklyDigestMail;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyNewsletter extends Command
{
    protected $signature = 'newsletter:send-weekly';

    protected $description = 'Send weekly digest email to confirmed newsletter subscribers';

    public function handle(): int
    {
        $products = Product::approved()
            ->where('created_at', '>=', now()->subDays(7))
            ->byVotes()
            ->with(['user', 'category'])
            ->limit(5)
            ->get();

        if ($products->isEmpty()) {
            $this->info('No products from this week. Skipping newsletter.');
            return self::SUCCESS;
        }

        $subscribers = NewsletterSubscriber::where('confirmed', true)->get();

        if ($subscribers->isEmpty()) {
            $this->info('No confirmed subscribers. Skipping newsletter.');
            return self::SUCCESS;
        }

        $this->info("Sending weekly digest to {$subscribers->count()} subscribers...");

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->queue(new WeeklyDigestMail($products));
        }

        $this->info('Weekly digest queued successfully.');

        return self::SUCCESS;
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmationMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
            'name' => $request->name,
        ]);

        Mail::to($subscriber->email)->queue(new NewsletterConfirmationMail($subscriber));

        return back()->with('success', 'Thanks for subscribing! Please check your email to confirm.');
    }

    public function confirm(string $token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $subscriber->update(['confirmed' => true]);

        return redirect()->route('home')->with('success', 'Your subscription has been confirmed!');
    }
}

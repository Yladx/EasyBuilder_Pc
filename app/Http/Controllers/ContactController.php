<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function sendEmail(Request $request)
    {
        // Log the incoming request details
        Log::info('Contact Form Submission', [
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'message_length' => strlen($request->input('message'))
        ]);

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);

        // If validation fails, log and return error
        if ($validator->fails()) {
            Log::warning('Contact Form Validation Failed', [
                'errors' => $validator->errors()->toArray()
            ]);

            return redirect()->back()->with([
                'error' => $validator->errors()->first(),
                'type' => 'error'
            ]);
        }

        try {
            // Send email
            Mail::raw($request->input('message'), function ($message) use ($request) {
                $message->to(env('MAIL_FROM_ADDRESS', 'virtuciogerardmichael17@gmail.com'))
                    ->subject($request->input('subject'))
                    ->from($request->input('email'));
            });

            // Log successful email sending
            Log::info('Contact Email Sent Successfully', [
                'sender_email' => $request->input('email'),
                'subject' => $request->input('subject')
            ]);

            // Redirect back with success message
            return redirect()->back()->with([
                'success' => 'Your message has been sent successfully!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            // Log detailed error information
            Log::error('Email Sending Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sender_email' => $request->input('email'),
                'subject' => $request->input('subject')
            ]);

            // Redirect back with error message
            return redirect()->back()->with([
                'error' => 'Failed to send email. Please try again later.',
                'type' => 'error'
            ]);
        }
    }
}

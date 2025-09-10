<?php
namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GmailAdapter implements MailServiceInterface
{
    public function send(string $to, string $subject, string $body): bool
    {
        try {
            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)
                        ->subject($subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return true;
        } catch (\Exception $e) {
            Log::error("GmailAdapter error (raw): " . $e->getMessage());
            return false;
        }
    }

    public function sendTemplate(string $to, string $subject, string $view, array $data = []): bool
    {
        try {
            Mail::send($view, $data, function ($message) use ($to, $subject) {
                $message->to($to)
                        ->subject($subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return true;
        } catch (\Exception $e) {
            Log::error("GmailAdapter error (template): " . $e->getMessage());
            return false;
        }
    }
}

<?php
namespace App\Services;

interface MailServiceInterface
{
    public function send(string $to, string $subject, string $body): bool;

    public function sendTemplate(string $to, string $subject, string $view, array $data = []): bool;
}
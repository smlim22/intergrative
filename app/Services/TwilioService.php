<?php
namespace App\Services;
/**
 * Author : Adrean Goh
 */
use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');

        $this->twilio = new Client($sid, $token);
    }

    public function sendWhatsAppMessage($to, $body, $contentSid = null, $variables = [])
    {
        $to = str_starts_with($to, 'whatsapp:') ? $to : "whatsapp:$to";

        $data = [
            'from' => config('services.twilio.whatsapp_from'),
            'body' => $body,
        ];

        if ($contentSid) {
            $data['contentSid'] = $contentSid;
            $data['contentVariables'] = json_encode($variables);
        }

        return $this->twilio->messages->create($to, $data);
    }

    //MODIFY THIS
    public function sendInvoicePdf($to)
{
    $to = str_starts_with($to, 'whatsapp:') ? $to : "whatsapp:$to";

    // Google Drive direct download link
    $pdfUrl = "https://drive.google.com/uc?export=download&id=16STnq-IMn4nbdrJi9laaiVrMk_LU0MM-";

    return $this->twilio->messages->create(
        $to,
        [
            "from" => config('services.twilio.whatsapp_from'),
            "body" => "Here is your invoice 📄",
            "mediaUrl" => [$pdfUrl],
        ]
    );
}

    
}

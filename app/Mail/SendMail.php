<?php

namespace App\Mail;

use App\Models\Config as ConfigData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aushnet Communication Pvt Ltd.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        $emailContent = $this->data;
        $companyName = 'Cennec';
        $authLogo = 'login';

        if (auth()->check()) {
            $companyDetails = ConfigData::where(['user_id' => auth()->user()->id, 'type' => 'profile'])
                ->pluck('value', 'key')
                ->toArray();
            $companyName = $companyDetails['company_name'] ?? $companyName;
            $authLogo = 'auth';
        }
        $logoPath = asset('build/images/logo.png');
        $systemLogo = getSystemLogo($authLogo);

        if (! empty($systemLogo) && Storage::exists('public/uploads/website_icons/' . $systemLogo)) {
            $logoFilePath = Storage::url('public/uploads/website_icons/' . $systemLogo);
            $logoPath = asset($logoFilePath);
        }

        return $this->view('backend.email-notification')
            ->subject('Aushnet Communication Pvt Ltd.')
            ->with([
                'subject' => $emailContent['subject'],
                'emailContent' => $emailContent['email_body'],
                'companyName' => $companyName,
                'logoPath' => $logoPath,
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

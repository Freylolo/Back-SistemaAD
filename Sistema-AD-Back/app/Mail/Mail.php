<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class Mail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $text;
    public $attachments;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $text
     * @param array $attachments
     */
    public function __construct($subject, $text, $attachments = [])
    {
        $this->subject = $subject;
        $this->text = $text;
        $this->attachments = $attachments;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
     public function content(): Content
    {
        $defaultMessage = "Le saluda Administracion de Camino Real.";
        return new Content(
            view: 'emails.qrcode',
            with: [
                'subject' => $this->subject,
                'defaultMessage' => $defaultMessage,
                'text' => $this->text,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        $attachments = [];
        foreach ($this->attachments as $file) {
            if (file_exists($file)) {
                $attachments[] = [
                    'path' => $file,
                ];
            }
        }
        return $attachments;
    }

    /**
     * Build the message with attachments.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view('emails.qrcode')
                     ->subject($this->subject)
                     ->with([
                         'text' => $this->text,
                     ]);

        // Adjuntar los archivos si existen
        foreach ($this->attachments as $attachment) {
            $mail->attach($attachment['path']);
        }

        return $mail;
    }

    
}

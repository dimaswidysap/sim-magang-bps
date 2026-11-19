<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenerimaanMagangMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $namaLengkap;
    public string $password;

    public function __construct(string $namaLengkap, string $password)
    {
        $this->namaLengkap = $namaLengkap;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat! Penerimaan Magang di BPS & Informasi Akun SIM Magang',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.penerimaan-magang',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

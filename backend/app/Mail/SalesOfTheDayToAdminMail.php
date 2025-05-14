<?php

namespace App\Mail;
use Illuminate\Mail\Mailable;

class SalesOfTheDayToAdminMail extends Mailable
{
    public function __construct(public int $sales) {}

    public function build()
    {
        return $this->subject('Resumo diário de vendas')
            ->view('emails.sales-of-the-day-to-admin')
            ->with(['sales' => $this->sales]);
    }
}

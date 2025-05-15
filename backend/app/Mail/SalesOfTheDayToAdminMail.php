<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalesOfTheDayToAdminMail extends Mailable implements ShouldQueue
{
    public function __construct(public int $sales)
    {
    }

    public function build()
    {
        return $this->subject('Valor total de vendas do dia')
            ->view('emails.sales-of-the-day-to-admin')
            ->with(['sales' => $this->sales]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class DailySalesReportMail extends Mailable implements ShouldQueue
{
    public function __construct(public string $sellerName, public int $quantity, public int $total, public int $commission)
    {
    }

    public function build()
    {
        return $this->subject('Resumo diário de vendas')
            ->view('emails.daily-sales-report')
            ->with([
                'sellerName' => $this->sellerName,
                'quantity' => $this->quantity,
                'total' => $this->total ,
                'commission' => $this->commission ,
            ]);
    }

}

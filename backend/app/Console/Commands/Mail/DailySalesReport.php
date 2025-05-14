<?php

namespace App\Console\Commands\Mail;

use App\Models\Seller;
use Illuminate\Console\Command;
use App\Mail\DailySalesReportMail;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Scheduling\Schedule;

class DailySalesReport extends Command
{
    protected $signature = 'mail:daily-sales-report';
    protected $description = 'Envia e-mails de resumo diário aos vendedores';
    private SaleRepository $saleRepository;
 
    public function __construct(SaleRepository $saleRepository) {
        parent::__construct();
        $this->saleRepository = $saleRepository;
    }

    public function scheduled(Schedule $schedule): void
    {
        $schedule->dailyAt('19:00');
    }

    public function handle()
    {
        foreach (Seller::all() as $seller) {

            $sales = $this->saleRepository->getSalesBySellerAndDate($seller, today());
            sleep(2);

            try{
                Mail::to($seller->email)->send(new DailySalesReportMail(
                    $seller->name,
                    $sales['quantity'],
                    $sales['total'],
                    $sales['commission']
                ));

                $this->info('E-mail enviado para o vendedor(a) ' . $seller->name);
            } catch (\Exception $e) {
                $this->error("Erro ao enviar e-mail para o vendedor {$seller->name}: {$e->getMessage()}");
            }
        }
    
    }
    
}

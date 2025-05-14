<?php

namespace App\Console\Commands\Mail;

use Illuminate\Console\Command;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\SalesOfTheDayToAdminMail;
use Illuminate\Console\Scheduling\Schedule;

class SalesOfTheDayToAdmin extends Command
{
    protected $signature = 'mail:sellers-of-the-day-to-admin';
    protected $description = 'Envia um e-mail para o administrador com a soma das vendas efetuadas no dia.';
    private SaleRepository $saleRepository;

    public function __construct(SaleRepository $saleRepository)
    {
        parent::__construct();
        $this->saleRepository = $saleRepository;
    }

    public function scheduled(Schedule $schedule): void
    {
        $schedule->dailyAt('23:00');
    }

    public function handle()
    {
        $sales = $this->saleRepository->getSalesByDate(today());
        sleep(2);

        try {
            Mail::to(env('ADMIN_EMAIL'))->send(new SalesOfTheDayToAdminMail($sales));
            $this->info('E-mail enviado para o administrador');
        } catch (\Exception $e) {
            $this->error("Erro ao enviar e-mail para o administrador: {$e->getMessage()}");
        }

    }

}

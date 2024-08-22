<?php

namespace App\Jobs;

use App\Http\Controllers\SaiuPraEntrega\SaiuPraEntregaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class sendRastreioSaiuPraEnrega implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $traking;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($traking)
    {
        $this->traking = $traking;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $object = [
            "description" => "PEDIDOs",
            "customer_name" => "Diitudo",
            "customer_email" => "manual@manual.com",
            "customer_phone" => "19999999999",
            "tracking_code" => $this->traking,
            "shipping_company" => "correios",
            "items" => [
                [
                    "name" => "produto drop",
                    "quantity" => 1,
                    "value" => 0
                ]
            ]
        ];

        Log::alert(json_encode($object));
        (new SaiuPraEntregaService())->createPackage($object);
    }
}

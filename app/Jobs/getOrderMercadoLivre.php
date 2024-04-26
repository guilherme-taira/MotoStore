<?php

namespace App\Jobs;

use App\Http\Controllers\Orders\MercadoLivre\MercadolivreOrderController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class getOrderMercadoLivre implements ShouldQueue
{

    private $id_mercadolivre;
    private $access_token;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id_mercadolivre,$access_token)
    {
        $this->id_mercadolivre = $id_mercadolivre;
        $this->access_token = $access_token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $MercadolivreOrderController = new MercadolivreOrderController($this->getIdMercadolivre(), $this->getAccessToken());
        $MercadolivreOrderController->resource();
    }

    /**
     * Get the value of id_mercadolivre
     */
    public function getIdMercadolivre()
    {
        return $this->id_mercadolivre;
    }

    /**
     * Get the value of access_token
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }
}

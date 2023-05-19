<?php

namespace App\Jobs;

use App\Http\Controllers\MercadoLivre\Job\getProductDataController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class getStockPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private String $mercadolivreId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mercadolivreId)
    {
        $this->mercadolivreId = $mercadolivreId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $atualizar = new getProductDataController();
        $atualizar->getProduct($this->getMercadolivreId());
    }

    /**
     * Get the value of mercadolivreId
     */
    public function getMercadolivreId(): String
    {
        return $this->mercadolivreId;
    }

    /**
     * Set the value of mercadolivreId
     */
    public function setMercadolivreId(String $mercadolivreId): self
    {
        $this->mercadolivreId = $mercadolivreId;

        return $this;
    }
}

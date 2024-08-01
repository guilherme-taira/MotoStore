<?php

namespace App\Jobs;

use App\Http\Controllers\Shopify\ShopifyDraftOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class putDraftShopifyOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $Draftorder;
    public $getLink;
    public $mercadoLivreId;
    public $comprador;
    public $seller;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($getLink,$Draftorder,$mercadoLivreId,$comprador,$seller)
    {
        $this->getLink = $getLink;
        $this->Draftorder = $Draftorder;
        $this->mercadoLivreId = $mercadoLivreId;
        $this->comprador = $comprador;
        $this->seller = $seller;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // IMPLEMENTAR A CLASSE DRAFT
        $draftOrder = new ShopifyDraftOrder($this->getLink,$this->Draftorder,$this->mercadoLivreId,$this->comprador,$this->seller);
        $draftOrder->resource();
    }
}

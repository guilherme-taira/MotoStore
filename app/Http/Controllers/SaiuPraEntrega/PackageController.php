<?php

namespace App\Http\Controllers\SaiuPraEntrega;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $saiuPraEntregaService;

    public function __construct(SaiuPraEntregaService $saiuPraEntregaService)
    {
        $this->saiuPraEntregaService = $saiuPraEntregaService;
    }

    public function createPackage(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'tracking_code' => 'required|string',
            'shipping_company' => 'required|string',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.value' => 'required|numeric',
        ]);

        $response = $this->saiuPraEntregaService->createPackage($data);

        return response()->json($response);
    }
}

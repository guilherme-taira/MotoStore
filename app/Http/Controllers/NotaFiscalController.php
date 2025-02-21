<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\financeiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotaFiscalController extends Controller
{
    public function uploadNotaFiscal(Request $request, $order_id)
    {

        try {
            Log::alert($request->all());
            // Valida se o arquivo foi enviado e se é um PDF
            $request->validate([
                'nota_fiscal' => 'required|mimes:pdf|max:2048' // Aceita apenas PDF de até 2MB
            ]);

            if ($request->hasFile('nota_fiscal')) {
                $file = $request->file('nota_fiscal');
                $fileName = 'nf_' . $order_id . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Salva no storage (pasta `public/notas_fiscais`)
                $filePath = $file->storeAs('public/notas_fiscais', $fileName);

                // Atualiza a ordem com o nome do arquivo
                $order = financeiro::find($order_id);
                if ($order) {
                    $order->nota_fiscal = str_replace('public/', '', $filePath);
                    $order->save();
                }

                return back()->with('success', 'Nota Fiscal anexada com sucesso!');
            }

            return back()->with('error', 'Erro ao anexar a Nota Fiscal.');
        } catch (\Exception $th) {
           Log::alert($th->getMessage());
        }

    }
}

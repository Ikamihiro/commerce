<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExtratoController extends Controller
{
    public function __invoke(Request $request)
    {
        $extrato = $this->getExtrato(
            $request->input('start_date', now()->subMonth()->toDateString()),
            $request->input('finish_date', now()->toDateString()),
            $request->input('offset', 0),
            $request->input('limit', 10),
            $request->input('order', 'asc')
        );

        $saldo = $this->getSaldo();

        return view('admin.extrato.index', compact('extrato', 'saldo'));
    }

    private function getExtrato(string $startDate, string $finishDate, int $offset = 0, int $limit = 10, string $order = 'asc'): array
    {
        $response = Http::asaas()
            ->get('financialTransactions', [
                'startDate' => $startDate,
                'finishDate' => $finishDate,
                'offset' => $offset,
                'limit' => $limit,
                'order' => $order,
            ]);

        if ($response->failed()) {
            throw new \Exception('Erro ao buscar extratos: ' . $response->body());
        }

        return $response->json();
    }

    private function getSaldo()
    {
        $response = Http::asaas()
            ->get('finance/balance');

        if ($response->failed()) {
            throw new \Exception('Erro ao buscar saldo: ' . $response->body());
        }

        return $response->json();
    }
}

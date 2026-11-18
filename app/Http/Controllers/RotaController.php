<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RotaController extends Controller
{
    public function calcular(Request $request)
    {
        $dados = $request->validate([
            'origem.longitude' => 'required|numeric',
            'origem.latitude' => 'required|numeric',
            'destino.longitude' => 'required|numeric',
            'destino.latitude' => 'required|numeric',
        ]);

        $coordenadas = [
            [
                (float) $dados['origem']['longitude'],
                (float) $dados['origem']['latitude'],
            ],
            [
                (float) $dados['destino']['longitude'],
                (float) $dados['destino']['latitude'],
            ],
        ];

        $chave = 'rota_' . md5(json_encode($coordenadas));

        return Cache::remember($chave, now()->addDays(30), function () use ($coordenadas) {
            $response = Http::withHeaders([
                'Authorization' => config('services.ors.key'),
            ])->post(
                'https://api.heigit.org/openrouteservice/v2/directions/foot-walking',
                [
                    'coordinates' => $coordenadas,
                    'instructions' => false,
                ]
            );

            $response->throw();

            $resumo = $response->json('routes.0.summary');

            return [
                'distancia' => $resumo['distance'] / 1000,
                'tempo_de_deslocamento' => (int) round($resumo['duration'] / 60),
            ];
        });
    }
}

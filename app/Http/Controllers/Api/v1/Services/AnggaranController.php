<?php

namespace App\Http\Controllers\Api\v1\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnggaranController extends Controller
{
    //
    public function index($tahun, $periode, Request $request)
    {
        $kode_skpd = $request->get('payload')->opd->kode_opd;
        $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => 'Bearer '
                    ])
                    ->withQueryParameters([
                        'tahun' => $tahun,
                        'periode' => $periode,
                        'kode_skpd' => $kode_skpd,
                    ])
                    ->get('http://127.0.0.1/api/data/program-anggaran');
        return response()->json([
            "success" => true,
            "message" => $response->json(),
            "data" => $response->json()
        ]);        
    }


    public function getAnggaranOpd($tahun, $periode, $kode_skpd, Request $request)
    {
        $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => 'Bearer '
                    ])
                    ->withQueryParameters([
                        'tahun' => $tahun,
                        'periode' => $periode,
                        'kode_skpd' => $kode_skpd,
                    ])
                    ->get('http://127.0.0.1/api/data/program-anggaran');
        return response()->json([
            "success" => true,
            "message" => $response->json(),
            "data" => $response->json()
        ]);        
    }
}

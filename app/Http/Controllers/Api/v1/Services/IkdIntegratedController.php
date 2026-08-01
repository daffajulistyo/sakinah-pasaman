<?php

/*
 * NOTE: Controller ini DI-NONAKTIFKAN (di-comment) karena diganti oleh
 * master lokal (AnggaranController). Simpan sebagai referensi jika suatu
 * saat integrasi dengan IKD BPKAD dibutuhkan kembali.
 *
namespace App\Http\Controllers\Api\v1\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IkdIntegratedController extends Controller
{
    //
    public function getProgramAnggaranSkpd($idskpd, $year, Request $request)
    {
        $ikdtoken = $request->get('payload')->ikdtoken;
        $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $ikdtoken
                    ])
                    ->withQueryParameters([
                        'tahun' => $year,
                        'idskpd' => $idskpd
                    ])
                    ->get(env('IKD_URL') . '/api/anggaran/skpd-program');
        return response()->json([
            "success" => true,
            "message" => $response->json()['message'],
            "data" => $response->json()['result']
        ]);
    }
}
*/

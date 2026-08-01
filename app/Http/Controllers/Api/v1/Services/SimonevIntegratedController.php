<?php

/*
 * NOTE: Controller ini DI-NONAKTIFKAN (di-comment) karena diganti oleh
 * master lokal (AnggaranController). Simpan sebagai referensi jika suatu
 * saat integrasi dengan SIMONEV Bappeda dibutuhkan kembali.
 *
namespace App\Http\Controllers\Api\v1\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class SimonevIntegratedController extends Controller
{
    //
    public function getProgramAnggaranSkpd($idskpd, $year, Request $request)
    {
        $response = Http::withoutVerifying()
                    ->get(env('SIMONEV_URL') . '/'. $year .'/api/api_realisasi/idopd/'.$idskpd.'/tahun/'.$year);
        return response()->json([
            "success" => true,
            "message" => "Data program kegiatan dan anggaran dari aplikasi eMonev",
            "data" => $response->json()
        ]);
    }
}
*/

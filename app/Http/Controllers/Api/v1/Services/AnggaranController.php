<?php

namespace App\Http\Controllers\Api\v1\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggaranController extends Controller
{
    private function getProgramData($tahun, $kode_skpd)
    {
        $programs = DB::table('cascading')
            ->join('master_opd', DB::raw('CAST(master_opd.kode_opd AS UNSIGNED)'), '=', 'cascading.id_skpd')
            ->where('master_opd.kode_opd', $kode_skpd)
            ->where('cascading.tahun', $tahun)
            ->select(
                'cascading.id_program',
                'cascading.kode_program',
                'cascading.nama_program',
                'cascading.id_skpd'
            )
            ->distinct()
            ->get();

        return $programs->toArray();
    }

    public function index($tahun, $periode, Request $request)
    {
        $kode_skpd = $request->attributes->get('payload')->opd->kode_opd;
        $programs = $this->getProgramData($tahun, $kode_skpd);

        if (empty($programs)) {
            $programs = DB::table('cascading')
                ->select('id_program', 'kode_program', 'nama_program', 'id_skpd')
                ->distinct()
                ->get()
                ->toArray();
        }

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => [
                'data_program' => $programs,
            ],
        ]);
    }

    public function getAnggaranOpd($tahun, $periode, $kode_skpd, Request $request)
    {
        $programs = $this->getProgramData($tahun, $kode_skpd);

        if (empty($programs)) {
            $programs = DB::table('cascading')
                ->select('id_program', 'kode_program', 'nama_program', 'id_skpd')
                ->distinct()
                ->get()
                ->toArray();
        }

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => [
                'data_program' => $programs,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\v1\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggaranController extends Controller
{
    private function getProgramData($tahun, $kode_skpd)
    {
        // ponytail: query master_program instead of cascading (circular dependency)
        $programs = DB::table('master_program')
            ->where('kode_skpd', $kode_skpd)
            ->where('tahun', $tahun)
            ->where('is_active', true)
            ->select(
                'id as id_program',
                'kode_program',
                'nama_program',
                'kode_skpd as id_skpd'
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
            // ponytail: fallback to all active programs if none for this OPD
            $programs = DB::table('master_program')
                ->where('is_active', true)
                ->select('id as id_program', 'kode_program', 'nama_program', 'kode_skpd as id_skpd')
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
            // ponytail: fallback to all active programs if none for this OPD
            $programs = DB::table('master_program')
                ->where('is_active', true)
                ->select('id as id_program', 'kode_program', 'nama_program', 'kode_skpd as id_skpd')
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

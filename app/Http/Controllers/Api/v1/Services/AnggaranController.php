<?php

namespace App\Http\Controllers\Api\v1\Services;

use App\Http\Controllers\Controller;
use App\Models\MasterProgram;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    private function getProgramData($tahun, $kode_skpd)
    {
        $programs = MasterProgram::with(['kegiatans.subKegiatans'])
            ->where('kode_skpd', $kode_skpd)
            ->where('tahun', $tahun)
            ->where('is_active', true)
            ->get();

        if ($programs->isEmpty()) {
            $programs = MasterProgram::with(['kegiatans.subKegiatans'])
                ->where('is_active', true)
                ->get();
        }

        return $programs->map(function ($prog) {
            return [
                'id_program' => $prog->id,
                'kode_program' => $prog->kode_program,
                'nama_program' => $prog->nama_program,
                'id_skpd' => $prog->kode_skpd,
                'anggaran' => (float) $prog->anggaran,
                'data_kegiatan' => $prog->kegiatans->where('is_active', true)->values()->map(function ($keg) {
                    return [
                        'id_giat' => $keg->id,
                        'kode_kegiatan' => $keg->kode_kegiatan,
                        'nama_kegiatan' => $keg->nama_kegiatan,
                        'anggaran' => (float) $keg->anggaran,
                        'data_sub_kegiatan' => $keg->subKegiatans->where('is_active', true)->values()->map(function ($sub) {
                            return [
                                'id_sub_giat' => $sub->id,
                                'kode_sub_kegiatan' => $sub->kode_sub_kegiatan,
                                'nama_sub_kegiatan' => $sub->nama_sub_kegiatan,
                                'anggaran' => (float) $sub->anggaran,
                            ];
                        }),
                    ];
                }),
            ];
        })->toArray();
    }

    public function index($tahun, $periode, Request $request)
    {
        $kode_skpd = $request->attributes->get('payload')->opd->kode_opd;
        $programs = $this->getProgramData($tahun, $kode_skpd);

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

        return response()->json([
            'success' => true,
            'message' => null,
            'data' => [
                'data_program' => $programs,
            ],
        ]);
    }
}

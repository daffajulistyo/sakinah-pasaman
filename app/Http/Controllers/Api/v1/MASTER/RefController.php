<?php

namespace App\Http\Controllers\Api\v1\MASTER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefController extends Controller
{
    public function eselon()
    {
        return response()->json(['success' => true, 'data' => DB::table('ref_eselon')->where('is_active', true)->orderBy('level')->orderBy('kode')->get()]);
    }

    public function golongan()
    {
        return response()->json(['success' => true, 'data' => DB::table('ref_golongan')->where('is_active', true)->orderBy('kode')->get()]);
    }

    public function jenisJabatan()
    {
        return response()->json(['success' => true, 'data' => DB::table('ref_jenis_jabatan')->where('is_active', true)->orderBy('kode')->get()]);
    }

    public function jabatan(Request $request)
    {
        $query = DB::table('ref_jabatan')->where('is_active', true);
        if ($request->jenis_id) {
            $query->where('ref_jenis_jabatan_id', $request->jenis_id);
        }
        return response()->json(['success' => true, 'data' => $query->orderBy('nama')->get()]);
    }

    public function subOpd(Request $request)
    {
        $query = DB::table('ref_sub_opd')->where('is_active', true);
        if ($request->opd_id) {
            $query->where('master_opd_id', $request->opd_id);
        }
        return response()->json(['success' => true, 'data' => $query->orderBy('nama')->get()]);
    }
}

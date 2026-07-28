<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RefController extends Controller
{
    public function opd()
    {
        return response()->json(DB::table('master_opd')->where('is_active', true)->orderBy('nama_opd')->get(['id', 'kode_opd', 'nama_opd']));
    }

    public function eselon()
    {
        return response()->json(DB::table('master_eselon')->where('is_active', true)->orderBy('level')->orderBy('kode')->get(['id', 'kode', 'nama', 'level']));
    }

    public function golongan()
    {
        return response()->json(DB::table('master_golongan')->where('is_active', true)->orderBy('kode')->get(['id', 'kode', 'golongan', 'pangkat']));
    }

    public function jenisJabatan()
    {
        return response()->json(DB::table('master_jenis_jabatan')->where('is_active', true)->orderBy('kode')->get(['id', 'kode', 'nama']));
    }

    public function jabatan()
    {
        return response()->json(DB::table('master_jabatan')->where('is_active', true)->orderBy('nama')->get(['id', 'kode', 'nama', 'ref_jenis_jabatan_id']));
    }

    public function subOpd()
    {
        return response()->json(DB::table('master_sub_opd')->where('is_active', true)->orderBy('nama')->get(['id', 'kode', 'nama', 'master_opd_id']));
    }

    public function satuan()
    {
        return response()->json(DB::table('master_satuan')->where('is_active', true)->orderBy('satuan')->get(['id', 'satuan', 'keterangan']));
    }

    public function program()
    {
        return response()->json(DB::table('master_program')->where('is_active', true)->orderBy('kode_program')->get(['id', 'kode_program', 'nama_program']));
    }

    public function kegiatan()
    {
        return response()->json(DB::table('master_kegiatan')->where('is_active', true)->orderBy('kode_kegiatan')->get(['id', 'kode_kegiatan', 'nama_kegiatan', 'master_program_id']));
    }

    public function subKegiatan()
    {
        return response()->json(DB::table('master_sub_kegiatan')->where('is_active', true)->orderBy('kode_sub_kegiatan')->get(['id', 'kode_sub_kegiatan', 'nama_sub_kegiatan', 'master_kegiatan_id']));
    }

    public function roles()
    {
        return response()->json(DB::table('roles')->orderBy('role_name')->get(['id', 'role_name as name']));
    }
}

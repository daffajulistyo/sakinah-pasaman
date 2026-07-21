<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Log\LogAccess;
use App\Helpers\JwtAuthentication;
use App\Http\Controllers\Controller;
use App\Models\Data\UserSakip;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Models\Pegawai as PegawaiModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PegawaiAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIP atau password salah.',
                ], 401);
            }

            $pegawai = PegawaiModel::where('user_id', auth()->user()->id)->first();
            if (!$pegawai) {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan.',
                ], 401);
            }

            if (!auth()->user()->is_active) {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Akun anda telah dinonaktifkan.',
                ], 401);
            }

            $usersakip = UserSakip::where(['user_id' => auth()->user()->id])->first();
            $opd = null;
            if ($usersakip && $usersakip->master_opd_id) {
                $dataOpd = MasterOpd::find($usersakip->master_opd_id);
                if ($dataOpd) {
                    $opd = [
                        "id" => $dataOpd->id,
                        "nama_opd" => $dataOpd->nama_opd,
                        "alias_opd" => $dataOpd->alias_opd ?? null,
                        "kode_opd" => $dataOpd->kode_opd,
                        "kode_sub_opd" => $dataOpd->kode_sub_opd ?? null,
                    ];
                }
            }

            $eselonNm = $pegawai->ref_eselon_id ? ($pegawai->refEselon->nama ?? null) : null;
            $eselonId = $pegawai->ref_eselon_id ? ($pegawai->refEselon->kode ?? '99') : '99';
            $golongan = $pegawai->ref_golongan_id ? ($pegawai->refGolongan->golongan ?? null) : null;
            $pangkat = $pegawai->ref_golongan_id ? ($pegawai->refGolongan->pangkat ?? null) : null;
            $jnsJbtnNm = $pegawai->ref_jenis_jabatan_id ? ($pegawai->refJenisJabatan->nama ?? null) : null;
            $jabatanNm = $pegawai->ref_jabatan_id ? ($pegawai->refJabatan->nama ?? null) : null;

            $payload = [
                "username" => $pegawai->nip,
                "name" => $pegawai->nama,
                "nip" => $pegawai->nip,
                "level" => "Pegawai",
                "role" => "39d57ab8-c480-4c61-a5d8-a662c5b66e27",
                "opd" => $opd,
                "eselon_id" => $eselonId,
                "eselon_nm" => $eselonNm,
                "golongan" => $golongan,
                "pangkat" => $pangkat,
                "jns_jbtn_nm" => $jnsJbtnNm,
                "jabatan_nm" => $jabatanNm,
                "jenjang" => $pegawai->jenjang,
            ];

            $token_access = JwtAuthentication::create($payload);

            LogAccess::create([
                "id" => Str::uuid(),
                "user" => auth()->user()->username,
                "ip_address" => $request->ip(),
                "user_agent" => $request->header('User-Agent'),
                "unix_time" => time(),
            ]);

            return response()->json([
                'success' => true,
                'token_access' => $token_access,
                'message' => 'Login berhasil',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 422,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}

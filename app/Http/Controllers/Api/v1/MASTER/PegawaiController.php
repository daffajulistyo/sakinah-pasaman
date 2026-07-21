<?php

namespace App\Http\Controllers\Api\v1\MASTER;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pegawai as PegawaiModel;
use App\Models\User;
use App\Models\Data\UserSakip;
use App\Models\Sakip\MASTER\MasterOpd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function list(Request $request)
    {
        try {
            $query = PegawaiModel::with([
                'refEselon',
                'refGolongan',
                'refJenisJabatan',
                'refJabatan',
                'masterOpd',
                'user',
            ]);

            if ($request->search) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('nip', 'ilike', "%{$s}%")
                      ->orWhere('nama', 'ilike', "%{$s}%");
                });
            }

            $data = $query->orderBy('nama')->paginate($request->per_page ?? 10);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 422, 'message' => $e->getMessage()], 422);
        }
    }

    public function create(Request $request)
    {
        try {
            $payload = $request->get('payload');
            DB::beginTransaction();

            $request->validate([
                'nip' => 'required|unique:pegawai,nip',
                'nama' => 'required',
                'password' => 'required|min:6',
            ]);

            $user = User::create([
                'id' => Str::uuid(),
                'name' => $request->nama,
                'username' => $request->nip,
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            if ($request->master_opd_id) {
                UserSakip::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'master_opd_id' => $request->master_opd_id,
                    'created_by' => $payload->username ?? null,
                ]);
            }

            $pegawai = PegawaiModel::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama' => $request->nama,
                'gelar_depan' => $request->gelar_depan,
                'gelar_belakang' => $request->gelar_belakang,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'master_opd_id' => $request->master_opd_id,
                'sub_opd_id' => $request->sub_opd_id,
                'sub_opd_nm' => $request->sub_opd_nm,
                'ref_eselon_id' => $request->ref_eselon_id,
                'ref_golongan_id' => $request->ref_golongan_id,
                'ref_jenis_jabatan_id' => $request->ref_jenis_jabatan_id,
                'ref_jabatan_id' => $request->ref_jabatan_id,
                'jenjang' => $request->jenjang,
                'is_active' => true,
                'created_by' => $payload->username ?? null,
            ]);

            // Assign role Pegawai
            $pegawaiRoleId = '39d57ab8-c480-4c61-a5d8-a662c5b66e27';
            DB::table('roleplay')->insert([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'role_id' => $pegawaiRoleId,
                'type' => 'core',
                'created_by' => $payload->username ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $pegawai->load(['refEselon', 'refGolongan', 'refJenisJabatan', 'refJabatan', 'masterOpd']),
                'message' => 'Pegawai berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 422, 'message' => $e->getMessage()], 422);
        }
    }

    public function read($id)
    {
        try {
            $pegawai = PegawaiModel::with([
                'refEselon', 'refGolongan', 'refJenisJabatan', 'refJabatan', 'masterOpd', 'user'
            ])->findOrFail($id);

            return response()->json(['success' => true, 'data' => $pegawai]);
        } catch (\Exception $e) {
            return response()->json(['status' => 422, 'message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $payload = $request->get('payload');
            DB::beginTransaction();

            $pegawai = PegawaiModel::findOrFail($id);

            $request->validate([
                'nip' => 'required|unique:pegawai,nip,' . $id,
                'nama' => 'required',
            ]);

            if ($pegawai->user_id && $request->password) {
                $user = User::find($pegawai->user_id);
                if ($user) {
                    $user->update([
                        'name' => $request->nama,
                        'username' => $request->nip,
                        'password' => Hash::make($request->password),
                    ]);
                }
            }

            if ($request->master_opd_id && $pegawai->user_id) {
                $userSakip = UserSakip::where('user_id', $pegawai->user_id)->first();
                if ($userSakip) {
                    $userSakip->update(['master_opd_id' => $request->master_opd_id, 'updated_by' => $payload->username ?? null]);
                } else {
                    UserSakip::create([
                        'id' => Str::uuid(),
                        'user_id' => $pegawai->user_id,
                        'master_opd_id' => $request->master_opd_id,
                        'created_by' => $payload->username ?? null,
                    ]);
                }
            }

            $pegawai->update([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'gelar_depan' => $request->gelar_depan,
                'gelar_belakang' => $request->gelar_belakang,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'master_opd_id' => $request->master_opd_id,
                'sub_opd_id' => $request->sub_opd_id,
                'sub_opd_nm' => $request->sub_opd_nm,
                'ref_eselon_id' => $request->ref_eselon_id,
                'ref_golongan_id' => $request->ref_golongan_id,
                'ref_jenis_jabatan_id' => $request->ref_jenis_jabatan_id,
                'ref_jabatan_id' => $request->ref_jabatan_id,
                'jenjang' => $request->jenjang,
                'updated_by' => $payload->username ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $pegawai->load(['refEselon', 'refGolongan', 'refJenisJabatan', 'refJabatan', 'masterOpd']),
                'message' => 'Pegawai berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 422, 'message' => $e->getMessage()], 422);
        }
    }

    public function delete($id)
    {
        try {
            $pegawai = PegawaiModel::findOrFail($id);
            $pegawai->update(['is_active' => false]);

            if ($pegawai->user_id) {
                User::where('id', $pegawai->user_id)->update(['is_active' => false]);
            }

            return response()->json(['success' => true, 'message' => 'Pegawai berhasil dinonaktifkan']);
        } catch (\Exception $e) {
            return response()->json(['status' => 422, 'message' => $e->getMessage()], 422);
        }
    }
}

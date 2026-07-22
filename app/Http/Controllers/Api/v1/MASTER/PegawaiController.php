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
            $jwtPayload = $request->attributes->get('payload');
            $createdBy = $jwtPayload->username ?? null;
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

            // ponytail: auto-fill OPD from JWT payload (Admin OPD gets their OPD automatically)
            $masterOpdId = $request->master_opd_id ?: ($jwtPayload->opd->id ?? null);

            if ($masterOpdId) {
                UserSakip::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'master_opd_id' => $masterOpdId,
                    'created_by' => $createdBy,
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
                'master_opd_id' => $masterOpdId,
                'sub_opd_id' => $request->sub_opd_id,
                'sub_opd_nm' => $request->sub_opd_nm,
                'ref_eselon_id' => $request->ref_eselon_id,
                'ref_golongan_id' => $request->ref_golongan_id,
                'ref_jenis_jabatan_id' => $request->ref_jenis_jabatan_id,
                'ref_jabatan_id' => $request->ref_jabatan_id,
                'jenjang' => $request->jenjang,
                'is_active' => $request->is_active ?? true,
                'created_by' => $createdBy,
            ]);

            // Assign roles
            $roleIds = $request->role_ids ?? ($request->role_id ? [$request->role_id] : ['39d57ab8-c480-4c61-a5d8-a662c5b66e27']);
            if (!is_array($roleIds)) $roleIds = [$roleIds];
            foreach ($roleIds as $rid) {
                if ($rid) {
                    DB::table('roleplay')->insert([
                        'id' => Str::uuid(),
                        'user_id' => $user->id,
                        'role_id' => $rid,
                        'type' => 'core',
                        'created_by' => $createdBy,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

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

            $roleplay = DB::table('roleplay')
                ->join('roles', 'roleplay.role_id', '=', 'roles.id')
                ->where('roleplay.user_id', $pegawai->user_id)
                ->select('roles.id as role_id', 'roles.role_name as role_name')
                ->get();

            $result = $pegawai->toArray();
            $result['role_ids'] = $roleplay->pluck('role_id')->toArray();
            $result['roles'] = $roleplay->toArray();

            return response()->json(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['status' => 422, 'message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $jwtPayload = $request->attributes->get('payload');
            $updatedBy = $jwtPayload->username ?? null;
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
                    $userSakip->update(['master_opd_id' => $request->master_opd_id, 'updated_by' => $updatedBy]);
                } else {
                    UserSakip::create([
                        'id' => Str::uuid(),
                        'user_id' => $pegawai->user_id,
                        'master_opd_id' => $request->master_opd_id,
                        'created_by' => $updatedBy,
                    ]);
                }
            } elseif ($jwtPayload->opd->id ?? null) {
                // ponytail: ensure user_sakip exists for OPD auto-assignment
                $userSakip = UserSakip::where('user_id', $pegawai->user_id)->first();
                if (!$userSakip) {
                    UserSakip::create([
                        'id' => Str::uuid(),
                        'user_id' => $pegawai->user_id,
                        'master_opd_id' => $jwtPayload->opd->id,
                        'created_by' => $updatedBy,
                    ]);
                }
            }

            $roleIds = $request->role_ids ?? ($request->role_id ? [$request->role_id] : null);
            if ($roleIds) {
                if (!is_array($roleIds)) $roleIds = [$roleIds];
                DB::table('roleplay')->where('user_id', $pegawai->user_id)->delete();
                foreach ($roleIds as $rid) {
                    if ($rid) {
                        DB::table('roleplay')->insert([
                            'id' => Str::uuid(),
                            'user_id' => $pegawai->user_id,
                            'role_id' => $rid,
                            'type' => 'core',
                            'created_by' => $updatedBy,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
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
                'is_active' => $request->is_active ?? $pegawai->is_active,
                'updated_by' => $updatedBy,
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

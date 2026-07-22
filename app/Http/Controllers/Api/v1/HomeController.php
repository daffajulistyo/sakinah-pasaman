<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\JwtAuthentication;
use App\Http\Controllers\Controller;
use App\Models\Managements\Roleplay;
use App\Models\Managements\Roles;
    use App\Models\Pegawai as PegawaiModel;
    use App\Models\Data\UserSakip;
    use App\Models\Sakip\MASTER\MasterOpd;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function me(Request $request)
    {
        return response()->json([
            "success" => true,
            "message" => "My Verified Profile",
            "data" => $request->attributes->get('payload')
        ], 200);
    }

    public function getMyRoles(Request $request)
    {
        $payload = $request->attributes->get('payload');

        $user = User::where('username', $payload->username)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        // ponytail: exclude legacy 'Admin' role, show only operational roles matching ref
        $roles = Roleplay::select('roleplay.id', 'roleplay.role_id', 'roles.role_name')
            ->from('roleplay')
            ->join('roles', 'roles.id', '=', 'roleplay.role_id')
            ->where('user_id', $user->id)
            ->whereNotIn('roles.role_name', ['Admin'])
            ->get();

        return response()->json(['success' => true, 'data' => $roles]);
    }

    public function changeMyRole(Request $request)
    {
        $payload = $request->attributes->get('payload');
        $current_role = $payload->role;
        $new_role = $request->get('role_id');
        if ($current_role == $new_role) {
            return response()->json(['success' => false, 'message' => 'Current role and new role are the same'], 400);
        }
        $roleplay_id = $request->get('roleplay_id');
        $roleplay = Roleplay::find($roleplay_id);
        if (!$roleplay) {
            return response()->json(['success' => false, 'message' => 'Role tidak ditemukan'], 404);
        }

        $user = User::find($roleplay->user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $role = Roles::find($new_role);

        $pegawai = PegawaiModel::where('user_id', $user->id)->first();

        $jns_jbtn_id = null;
        $jns_jbtn_nm = null;
        $jabatan_nm = null;
        $jabatan_id = null;
        $eselon_id = '99';
        $eselon_nm = null;

        if ($pegawai) {
            $jns_jbtn_nm = $pegawai->refJenisJabatan->nama ?? null;
            $jabatan_nm = $pegawai->refJabatan->nama ?? null;
            $jabatan_id = $pegawai->ref_jabatan_id;
            $eselon_id = $pegawai->refEselon->kode ?? '99';
            $eselon_nm = $pegawai->refEselon->nama ?? null;
        }

        $opd = null;
        $dataOpd = null;
        if ($pegawai && $pegawai->master_opd_id) {
            $dataOpd = MasterOpd::find($pegawai->master_opd_id);
        } else {
            $usersakip = UserSakip::where('user_id', $user->id)->first();
            if ($usersakip && $usersakip->master_opd_id) {
                $dataOpd = MasterOpd::find($usersakip->master_opd_id);
            }
        }
        if ($dataOpd) {
            $opd = [
                "id" => $dataOpd->id,
                "nama_opd" => $dataOpd->nama_opd,
                "kode_opd" => $dataOpd->kode_opd,
                "alias_opd" => $dataOpd->alias_opd ?? null,
            ];
        }

        $newPayload = [
            "username" => $user->username,
            "name" => $user->name,
            "nip" => $pegawai->nip ?? $user->username,
            "level" => $role->role_name,
            "role" => $role->id,
            "opd" => $opd,
            "jabatan_id" => $jabatan_id,
            "jns_jbtn_nm" => $jns_jbtn_nm,
            "jabatan_nm" => $jabatan_nm,
            "eselon_id" => $eselon_id,
            "eselon_nm" => $eselon_nm,
        ];

        $token_access = JwtAuthentication::create($newPayload);
        return response()->json([
            'success' => true,
            'token_access' => $token_access,
            'message' => 'Role changed successfully'
        ]);
    }
}

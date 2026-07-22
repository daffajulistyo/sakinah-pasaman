<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Log\LogAccess;
use App\Helpers\JwtAuthentication;
use App\Http\Controllers\Controller;
use App\Models\Data\UserSakip;
use App\Models\Sakip\MASTER\MasterOpd;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    protected $maxAttempts = 3; // 3;
    protected $decayMinutes = 2; // 2;
    public function authenticate(Request $request)
    {
        try{
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);
    
            if (Auth::attempt($credentials)) {
                $usersakip = UserSakip::where(['user_id' => auth()->user()->id])->first();
                if(!$usersakip){
                    Auth::logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Username or password is incorrect.',
                        'code' => '404'
                    ], 401);
                }
                if(!Auth::user()->is_active){
                    Auth::logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Your account has been disabled.',
                    ], 401);
                }
                //get role
                $myroles = auth()->user()->roleplay->load('roles');
                $thisroles = $myroles->pluck('roles')->toArray();

                // ponytail: prefer operational role (Admin_KDH > Admin_OPD > Pegawai) over generic Admin
                $priority = ['Admin_KDH', 'Admin_OPD', 'Pegawai', 'Superadmin', 'Admin'];
                usort($thisroles, function ($a, $b) use ($priority) {
                    $ai = array_search($a['role_name'], $priority);
                    $bi = array_search($b['role_name'], $priority);
                    $ai = $ai === false ? 999 : $ai;
                    $bi = $bi === false ? 999 : $bi;
                    return $ai - $bi;
                });

                //get OPD
                $opd = null;
                if($usersakip->master_opd_id !== null){
                    $dataOpd = MasterOpd::find($usersakip->master_opd_id);
                    $opd = [
                        "id" => $dataOpd->id,
                        "nama_opd" => $dataOpd->nama_opd,
                        "alias_opd" => $dataOpd->alias_opd ?? null,
                        "kode_opd" => $dataOpd->kode_opd,
                        "kode_sub_opd" => $dataOpd->kode_sub_opd ?? null
                    ];
                }

                $level = $thisroles[0]['role_name'];
                $role = $thisroles[0]['id'];
                $jabatan = 'admin';
                $nip = '-';
                $gender = 'Laki-laki';
                // $getTokenIkd = $this->getIkdTokenAccess();
                $payload = [
                    "username" => auth()->user()->username,
                    "name" => auth()->user()->name,
                    "nip" => $nip,
                    "jabatan" => $jabatan,
                    "jabatan_id" => null,
                    "gender" => $gender,
                    "level" => $level,
                    "role" => $role,
                    "opd" => $opd,
                    // "ikdtoken" => $getTokenIkd['response']['access_token'] ?? "",
                ];

                $token_access = JwtAuthentication::create($payload);

                LogAccess::create([
                    "id"            => Str::uuid(),
                    "user"          => auth()->user()->username,
                    "ip_address"    => $request->ip(),
                    "user_agent"    => $request->header('User-Agent'),
                    "unix_time"     => time()
                ]);
    
                return response()->json([
                    'success' => true,
                    'token_access' => $token_access,
                    'message' => 'Login Success',
                ]);
            }
    
            return response()->json([
                'success' => false,
                'message' => 'Username or password is incorrect.',
            ], 401);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 422,
                'message' => $e->getMessage()
            ],422);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\v1\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Log\LogAccess;
use App\Helpers\JwtAuthentication;
use App\Http\Controllers\Controller;
use App\Models\Sakip\Services\UserSimpeg;
use App\Models\Data\UserSakip;
use App\Models\User;
use App\Models\Sakip\MASTER\MasterOpd;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\DB;

class SimpegController extends Controller
{
    public function getPegawaiOpd(Request $request)
    {   
        $idskpd = $request->simpeg_opd_id;
        $token = "";

        $simpeg_opd_id = $request->get('payload')->opd->simpeg_opd_id;

        if($simpeg_opd_id==1)
        $url = "url simpeg";
            else
            $url = "url simpeg";

        $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => ''
                    ])
                    ->get($url);
        
        if(!$response){
            return response()->json([
                "success" => false,
                "message" => "Unable to Connect ",
                "data" => ""
            ]);
        }
        return response()->json([
            "success" => true,
            "message" => $response->json()['response'],
            "data" => $response->json()['result']
        ]);
    }


    public function loginPegawai(Request $request)
    {  
        $url = "url simpeg";

        
        try{
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'],
            ]);


            $response = Http::withoutVerifying()
                    ->withHeaders([
                        'X-Original' => 'foo',
                    ])->replaceHeaders([
                        'X-Replacement' => 'bar',
                    ])->post($url, $credentials);
           
             
            if(!$response){
                return response()->json([
                    "success" => false,
                    "message" => "Unable to Connect ",
                    "data" => ""
                ]);
            }
            else
            {  
                
                $data = $response->json();

                if($data['response']==0){
                    return response()->json([
                        "success" => false,
                        "message" => $data['message'],
                        "data" => ""
                    ]);
                }

                $pegawai = $data['result'];
                $nip = str_replace(" ", "", $pegawai[0]['nip']);
                $opd = $pegawai[0]['opd'];
                $nm_opd = $pegawai[0]['nm_opd'];
                $sub_opd = $pegawai[0]['sub_opd'];
                $nm_sub_opd = $pegawai[0]['nm_sub_opd'];
                $opd_simpeg = $pegawai[0]['opd'];
                $nama_asn = $pegawai[0]['nama_asn'];
                $eselon_id = $pegawai[0]['id_eselon'];
                $eselon_nm = $pegawai[0]['eselon'];
                $id_gol = $pegawai[0]['id_gol'];
                $golongan = $pegawai[0]['golongan'];
                $pangkat = $pegawai[0]['pangkat'];
                $jenjang = $pegawai[0]['jenjang'];
                $jabatan_id =$pegawai[0]['jabatan_id'];
                $jabatan_nm = $pegawai[0]['jabatan'];
                $jns_jbtn_id =$pegawai[0]['jns_jbtn_id'];
                $jns_jbtn_nm =$pegawai[0]['jns_jbtn_nm'];

                //get master_opd_id
                $master_opd = DB::table('master_opd')
                ->where('simpeg_opd_id', '=', $opd)
                ->first();
                
                $master_opd_id = $master_opd->id;

                
                /*--------- Insert User ----------*/
                $insert['name'] = $nama_asn;
                $insert['username'] = $nip;
                $insert['password'] = "ElPassword";
                $insert['current_role'] = "39d57ab8-c480-4c61-a5d8-a662c5b66e27";
                $insert['is_active'] = true;
                /*--------- Insert User ----------*/

                /*--------- Insert Simpeg ----------*/                
                    $insert_simpeg['master_opd_id'] = $master_opd_id;
                    $insert_simpeg['nip'] = $nip;
                    $insert_simpeg['opd_id'] = $opd;
                    $insert_simpeg['opd_nm'] = $nm_opd;
                    $insert_simpeg['sub_opd_id'] = $sub_opd;
                    $insert_simpeg['sub_opd_nm'] = $nm_sub_opd;
                    $insert_simpeg['jns_jbtn_id'] = $jns_jbtn_id;
                    $insert_simpeg['jns_jbtn_nm'] = $jns_jbtn_id;
                    $insert_simpeg['jabatan_id'] = $jabatan_id;
                    $insert_simpeg['jabatan_nm'] = $jabatan_nm;
                    $insert_simpeg['eselon_id'] = $eselon_id;
                    $insert_simpeg['eselon_nm'] = $eselon_nm;
                    $insert_simpeg['json_pegawai'] = json_encode($pegawai);
                    $insert_simpeg['is_active'] = true;
                /*--------- Insert Simpeg ----------*/

                $cek_user  = User::where('username', $nip)->count(); 
                if($cek_user <=0)
                {
                    $roles = ['39d57ab8-c480-4c61-a5d8-a662c5b66e27'];
                    $id = Str::uuid();
                    $user = User::create($insert);

                    $roles = collect($roles)->map(function($item) use($nip){
                            return [
                                "id" => Str::uuid(),
                                "role_id" => $item,
                                "type" => "common",
                                "created_by" => $nip,
                                "updated_by" => $nip,
                            ];
                        });
                        
                    $user->role()->attach($roles);


                    //INsert OPD User
                    $userSakip = UserSakip::create([
                        'id' => Str::uuid(),
                        'user_id' => $user->id,
                        'master_opd_id' => $master_opd_id,
                        "created_by" => $nip,
                        "updated_by" => $nip,
                    ]);
                    
                    $cek_usersimpeg = UserSimpeg::join('users', 'users.username', '=', 'user_simpeg.nip')
                                        ->where('user_simpeg.nip', $nip)->count();
                    if($cek_usersimpeg <=0){
                        // insert into table simpeg
                        $insert_simpeg['id'] = Str::uuid();
                        $insert_simpeg['user_id'] = $user->id;
                        $simpeg_user = UserSimpeg::create($insert_simpeg);    
                    }
                    else
                         $simpeg_user = UserSimpeg::where('nip', '=', $nip)                    
                                ->update($insert_simpeg); 

                }
                else
                {
                    //update User
                    $data_user = User::where('username', '=', $nip)->first();  
                    $Update_user = User::where('username', '=', $nip)->update($insert);  

                    //update OPD User
                    $opd_user = UserSakip::where('user_id', '=', $data_user->id)                    
                                ->update(['master_opd_id' => $master_opd_id ]);  
                    //update user simpeg
                    $cek_usersimpeg = UserSimpeg::join('users', 'users.username', '=', 'user_simpeg.nip')
                                        ->where('user_simpeg.nip', $nip)->count();
                    if($cek_usersimpeg <=0){
                        // insert into table simpeg
                        $insert_simpeg['id'] = Str::uuid();
                        $insert_simpeg['user_id'] = $data_user->id;
                        $simpeg_user = UserSimpeg::create($insert_simpeg);    
                    }
                    else
                         $simpeg_user = UserSimpeg::where('nip', '=', $nip)                    
                                ->update($insert_simpeg);    
                }


                //set biro jadi sekda
                //$biro = array('2876', '2863', '2809', '2596', '2796', '2836', '2889', '2823', '2849');
                //$new_opd = (in_array($opd_simpeg, $biro)) ? '36d2dee6-17b4-4ce8-87e0-01e097c36d6d' : $master_opd_id;

                //get OPD
                $opd = null;
                if(!empty($master_opd_id)){
                    $dataOpd = MasterOpd::find($master_opd_id);
                    
                    $opd = [
                        "id" => $dataOpd->id,
                        "nama_opd" => $dataOpd->nama_opd,
                        "kode_opd" => $dataOpd->kode_opd,
                        "alias_opd" => $dataOpd->alias_opd,
                        "ikd_opd_id" => $dataOpd->ikd_opd_id,
                        "simpeg_opd_id" => $dataOpd->simpeg_opd_id
                    ];
                }

                $level = 'Pegawai';
                $role='39d57ab8-c480-4c61-a5d8-a662c5b66e27';
                $getTokenIkd = $this->getIkdTokenAccess();


                
                $payload = [
                    "username" => $nip,
                    "name" => $nama_asn,
                    "nip" => $nip,
                    "level" => $level,
                    "role" => $role,
                    "opd" => $opd,
                    "opd_id" => $dataOpd->id,
                    "jns_jbtn_id" => $jns_jbtn_id,
                    "jns_jbtn_nm" => $jns_jbtn_nm,
                    "jabatan_id" => $jabatan_id,
                    "jabatan_nm" => $jabatan_nm,
                    "eselon_id" => $eselon_id,
                    "eselon_nm" => $eselon_nm,
                    "ikdtoken" => $getTokenIkd['response']['access_token'] ?? "",
                ];

                $token_access = JwtAuthentication::create($payload);

                LogAccess::create([
                    "id"            => Str::uuid(),
                    "user"          => $nip,
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
    
    private function getIkdTokenAccess()
    {
        $params = [
            'grant_type' =>  "client_credentials",
            'client_id'     =>  env("IKD_CLIENT_ID", ""),
            'client_secret' => env('IKD_CLIENT_SECRET', ""),
            'scope'         => "*" 
        ];
        try {
            $response = Http::withoutVerifying()->asForm()->post(env('IKD_URL','') . '/oauth/token', $params);
            if($response->failed()){
                return array(
                    "error" => true,
                    "message" => "failed response from api"
                );
            }
            return array(
                "error" => false,
                "message" => "success",
                "response" => $response->json()
            );
        } catch (\Exception $e) {
            return array(
                "error" => true,
                "message" => $e->getMessage()
            );
        }
    }
}

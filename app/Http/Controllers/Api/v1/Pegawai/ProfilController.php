<?php

namespace App\Http\Controllers\Api\v1\Pegawai;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;
use App\Models\Sakip\OPD\Atasan;
use App\Models\Sakip\Services\UserSimpeg;


class ProfilController extends Controller
{
   
    public function index(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $username = $request->attributes->get('payload')->username;
        $nip = $request->attributes->get('payload')->nip;

        $profile = UserSimpeg::where('nip', '=', $nip)->first();
        $atasan = Atasan::where('nip_pegawai', $nip)->where('is_active', true)->first();  
        
        $asd = array('profile'=>$profile, 'atasan'=>$atasan);
        return response()->json([
            'success' => true,
            'message' => 'Profile Pegawai '.$username.' ',
            'data' => $asd,
        ]);
    }

    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;

               //validasi payload
            $form = $request->validate([
                "nip_atasan" => "required|string",
                "nama_atasan" => "required|string",
                "jabatan_atasan" => "required|string",
                "unit_kerja_atasan" => "required|string"
            ]);
            $form['is_active'] = true;

            $cek = Atasan::where('nip_pegawai', '=', $nip)->count();
           
            if($cek <= 0 )
            {
                $form['nip_atasan'] = $request->nip_atasan;
                $form['nama_atasan'] = $request->nama_atasan;
                $form['jabatan_atasan'] = $request->jabatan_atasan;
                $form['unit_kerja_atasan'] = $request->unit_kerja_atasan;
                
                $form['nip_pegawai'] = $nip;
                $form['id'] = Str::uuid();
                $form['created_by'] = $request->attributes->get('payload')->username;
                
                // insert into table db
                $data = Atasan::create($form);
            }
            else
            {
                $form['nip_atasan'] = $request->nip_atasan;
                $form['nama_atasan'] = $request->nama_atasan;
                $form['jabatan_atasan'] = $request->jabatan_atasan;
                $form['unit_kerja_atasan'] = $request->unit_kerja_atasan;
                
                $form['updated_by'] = $request->attributes->get('payload')->username;

                $update = Atasan::where('nip_pegawai', '=', $nip)             
                ->update($form);
            }
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Atasan Pegawai.',
                'data' => $form,
            ]);
        } catch (\Throwable $th) {

            // handdle error
            return response()->json([
                "success" => false,
                "message" => "Something went wrong!",
                "errors" => $th->getMessage()
            ],500);
        }
    }

}

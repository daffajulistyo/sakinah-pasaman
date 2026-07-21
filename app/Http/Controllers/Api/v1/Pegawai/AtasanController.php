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


class AtasanController extends Controller
{
   
    public function list(Request $request)
    {
        $master_opd_id = $request->get('payload')->opd->id;
        $username = $request->get('payload')->username;
        $nip = $request->get('payload')->nip;

         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }

        $Atasan = Atasan::where('nip_pegawai', $nip)->get();
       
        $Atasan = $Atasan->map(function($dl) use($request) 
        {
            return [
                "id"            => $dl->id,
                "nip_pegawai"       => $dl->nip_pegawai,
                "nip_atasan"       => $dl->nip_atasan,
                "jabatan_atasan"       => $dl->jabatan_atasan,
                "unit_kerja_atasan"       => $dl->unit_kerja_atasan,
                "is_active"       => $dl->is_active
            ];
        });               


        return response()->json([
            'success' => true,
            'message' => 'Daftar Atasan Pegawai  '.$username.' ',
            'data' => $Atasan,
        ]);
    }

    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;
            $username = $request->get('payload')->username;
            $nip = $request->get('payload')->nip;

               //validasi payload
            $form = $request->validate([
                "nip_atasan" => "required|string",
                "jabatan_atasan" => "required|string",
                "unit_kerja_atasan" => "required|string"
            ]);

            $form['nip_atasan'] = $request->nip_atasan;
            $form['jabatan_atasan'] = $request->jabatan_atasan;
            $form['unit_kerja_atasan'] = $request->unit_kerja_atasan;

            // create uuid and assign author
            $form['nip_pegawai'] = $nip;
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->get('payload')->username;
            
            // insert into table db
            $data = Atasan::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Atasan Pegawai.',
                'data' => $data,
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

<?php

namespace App\Http\Controllers\Api\v1\Pegawai;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;
use App\Models\Sakip\OPD\PeriodeSkp;
use App\Models\Sakip\OPD\SasaranSkp;
use App\Models\Sakip\OPD\IndikatorSkp;
use App\Models\Sakip\OPD\LangkahSkp;


class SasaranSkpController extends Controller
{
   
    public function list(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $username = $request->attributes->get('payload')->username;
        $nip = $request->attributes->get('payload')->nip;
        $jabatan_id = $request->attributes->get('payload')->jabatan_id;
        $skp_id = $request->skp_id;

         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }

        $sasaran_skp = SasaranSkp::where('created_by', $username)
                                ->where('skp_id', '=', $skp_id)
                                ->get();
       
        $sasaran_skp = $sasaran_skp->map(function($ss) use($request, $username) 
        {       
                $sasaran_skp_id = $ss->id;
                $skp_id = $request->skp_id;
            
                $indikator_skp = IndikatorSkp::where('sasaran_skp_id', '=', $sasaran_skp_id)
                            ->where('skp_id', '=', $skp_id)
                            ->where('created_by', $username)
                            ->get();                
                $indikator_skp = $indikator_skp->map(function($is) use($request, $username) 
                {        
                    $skp_id = $request->skp_id;
                    $sasaran_skp_id = $is->sasaran_skp_id;
                    $indikator_skp_id = $is->id;
                    
                    $langkah = LangkahSkp::where('sasaran_skp_id', '=', $sasaran_skp_id)
                            ->where('skp_id', '=', $skp_id)
                            ->where('indikator_skp_id', '=', $indikator_skp_id)
                            ->where('created_by', $username)
                            ->get(); 
                        $langkah = $langkah->map(function($dl) use($request) 
                        { 
                             return [
                                "id"                => $dl->id,
                                "sasaran_skp_id"    => $dl->sasaran_skp_id,
                                "indikator_skp_id"  => $dl->indikator_skp_id,
                                "langkah"           => $dl->langkah,
                                "target_tw1"        => $dl->target_tw1,
                                "target_tw2"        => $dl->target_tw2,
                                "target_tw3"        => $dl->target_tw3,
                                "target_tw4"        => $dl->target_tw4
                            ];
                        }); 

                    return [
                        "id"                => $is->id,
                        "sasaran_skp_id"    => $is->sasaran_skp_id,
                        "indikator"         => $is->indikator,
                        "target"            => $is->target,
                        "master_satuan_id"  => $is->master_satuan_id,
                        "langkah_skp"       => $langkah,
                    ];
                }); 

            return [
                "id"                => $ss->id,
                "sasaran_atasan"    => $ss->sasaran_atasan,
                "sasaran"           => $ss->sasaran,
                "indikator"           => $indikator_skp,
            ];
        });               


        return response()->json([
            'success' => true,
            'message' => 'Daftar Sasaran Kinerja Pegawai  '.$username.' ',
            'data' => $sasaran_skp,
        ]);
    }


    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;
            

            if(!Str::isUuid($request->skp_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, SKP not Found',
                ], 422);
            }

            $skp = PeriodeSkp::find($request->skp_id);
            if (!$skp) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'SKP not found.',
                ], 404);
            }

               //validasi payload
            $form = $request->validate([
                "sasaran_atasan" => "required|string",
                "sasaran" => "required|string",
                "skp_id" => "required"
            ]);

            $form['sasaran_atasan'] = $request->sasaran_atasan;
            $form['sasaran'] = $request->sasaran;
            $form['skp_id'] = $request->skp_id;

            $form['id'] = Str::uuid();
            $form['created_by'] = $request->attributes->get('payload')->username;           
        
            // insert into table db
            $data = SasaranSkp::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Sasaran SKP Pegawai.',
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


    public function update($id, Request $request)
    {
        try {

            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;

            
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran SKP not Found',
                ], 422);
            }
             // cek existing rkpd
            $sasaran = SasaranSkp::find($id);
            if (!$sasaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran SKP not found sdsd.',
                ], 404);
            }

            if(!Str::isUuid($request->skp_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, SKP not Found',
                ], 422);
            }

            $skp = PeriodeSkp::find($request->skp_id);
            if (!$skp) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'SKP not found.',
                ], 404);
            }

           

            $form = $request->validate([                
                "sasaran_atasan" => "required|string",
                "sasaran" => "required|string"
            ]);
            
            $form['updated_by'] = $request->attributes->get('payload')->username;

            $update = SasaranSkp::where('id', '=', $id)
            ->where('created_by', '=',$username)              
            ->update($form);

            return response()->json([
                'success' => true,
                'message' => ' Sasaran SKP updated successfully.',
                'data' => $sasaran,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    public function delete($id, Request $request)
    {
        try {

            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;

            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }

            $cek_sasaran = SasaranSkp::where('id', '=', $id)
                        ->where('created_by', '=', $username)
                        ->get();

            if (count($cek_sasaran) <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => 'Sasaran tidak diampu oleh pegawai terkait.',
                ], 404);
            }

            $delete_sasaran = SasaranSkp::where('id', '=', $id)
                        ->where('created_by', '=', $username)
                        ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Sasaran deleted successfully.',
                'data' => $cek_sasaran,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

}

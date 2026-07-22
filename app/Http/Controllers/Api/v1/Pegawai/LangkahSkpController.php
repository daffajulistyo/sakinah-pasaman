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


class LangkahSkpController extends Controller
{
   
    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;
            $skp_id = $request->skp_id;
            $indikator_skp_id = $request->indikator_skp_id;
            
            /*-----------------Cek Detail SKP -------------------------*/
            if(!Str::isUuid($request->skp_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, SKP not Found Found',
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
            /*-----------------Cek Detail SKP -------------------------*/

            /*-----------------Cek Indikator SKP -------------------------*/          

            if(!Str::isUuid($request->indikator_skp_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator SKP not Found',
                ], 422);
            }

            $Indikator = IndikatorSkp::find($request->indikator_skp_id);
            if (!$Indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator SKP not found.',
                ], 404);
            }           
            /*-----------------Cek Indikator SKP -------------------------*/          


               //validasi payload
            $form = $request->validate([
                "langkah" => "required|string",
                "target_tw1"    => "required",
                "target_tw2"    => "required",
                "target_tw3"    => "required",
                "target_tw4"    => "required"
            ]);

            $form['skp_id'] = $request->skp_id;
            $form['indikator_skp_id'] = $request->indikator_skp_id;
            $form['satuan'] = $request->satuan;
            $form['target'] = $request->target;
            $form['keterangan'] = $request->keterangan;

            $form['id'] = Str::uuid();
            $form['created_by'] = $request->attributes->get('payload')->username;           
        
            // insert into table db
            $data = LangkahSkp::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Indikator SKP Pegawai.',
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
                    'message' => 'Invalid Id, Langkah SKP not Found',
                ], 422);
            }
             // cek existing rkpd
            $Langkah = LangkahSkp::find($id);
            if (!$Langkah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Langkah SKP not found sdsd.',
                ], 404);
            }

            /*cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->sasaran_skp_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran SKP not Found',
                ], 422);
            }
             // cek existing rkpd
            $sasaran = SasaranSkp::find($request->sasaran_skp_id);
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

             if(!Str::isUuid($request->indikator_skp_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator SKP not Found',
                ], 422);
            }

            $Indikator = IndikatorSkp::find($request->indikator_skp_id);
            if (!$Indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator SKP not found.',
                ], 404);
            }         */  

               //validasi payload
            $form = $request->validate([
                "langkah" => "required|string",
                "target_tw1"    => "required",
                "target_tw2"    => "required",
                "target_tw3"    => "required",
                "target_tw4"    => "required"
            ]);
            
            $form['keterangan'] = $request->keterangan;
            $form['updated_by'] = $request->attributes->get('payload')->username;

            $update = LangkahSkp::where('id', '=', $id)
            ->where('created_by', '=',$username)              
            ->update($form);


            return response()->json([
                'success' => true,
                'message' => ' Langkah SKP updated successfully.',
                'data' => $form,
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
                    'message' => 'Invalid Id, Indikator not Found',
                ], 422);
            }

            $cek_langkah = LangkahSkp::where('id', '=', $id)
                        ->where('created_by', '=', $username)
                        ->get();

            if (count($cek_langkah) <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => 'Langkah tidak diampu oleh pegawai terkait.',
                ], 404);
            }

            $delete_data = LangkahSkp::where('id', '=', $id)
                        ->where('created_by', '=', $username)
                        ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Indikator deleted successfully.',
                'data' => $delete_data,
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


    public function list(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $username = $request->attributes->get('payload')->username;
        $nip = $request->attributes->get('payload')->nip;
        $jabatan_id = $request->attributes->get('payload')->jabatan_id;
        $skp_id = $request->skp_id;
        $indikator_skp_id = $request->indikator_skp_id;

        $periode = PeriodeSkp::where('id', '=', $skp_id)->where('created_by', '=', $username)->count();
        if (!$periode) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'SKP not found.',
             ], 404);
         }

        
        $indikator = IndikatorSkp::where('id', '=', $indikator_skp_id)->where('created_by', '=', $username)->count();
        if (!$indikator) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Indikator not found.',
             ], 404);
         }

        $langkah_skp = LangkahSkp::where('created_by', '=', $username)
                                ->where('skp_id', '=', $skp_id)
                                ->where('indikator_skp_id', '=', $indikator_skp_id)
                                ->get();
       
        $langkah_skp = $langkah_skp->map(function($ss) use($request, $username) 
        {   
            return [
                "id"                   => $ss->id,
                "skp_id"               => $ss->skp_id,
                "indikator_skp_id"     => $ss->indikator_skp_id,
                "langkah"              => $ss->langkah,
                "target_tw1"           => $ss->target_tw1,
                "target_tw2"           => $ss->target_tw2,
                "target_tw3"           => $ss->target_tw3,
                "target_tw4"           => $ss->target_tw4,
                "satuan"               => $ss->satuan,
                "keterangan"           => $ss->keterangan
            ];
        });               


        return response()->json([
            'success' => true,
            'message' => 'Daftar Rerncana Aksi SKP Pegawai  '.$username.' ',
            'data' => $langkah_skp,
        ]);
    }


    public function list_realisasi(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $username = $request->attributes->get('payload')->username;
        $nip = $request->attributes->get('payload')->nip;
        $jabatan_id = $request->attributes->get('payload')->jabatan_id;
        $skp_id = $request->skp_id;
        $sasaran_skp_id = $request->sasaran_skp_id;
        $indikator_skp_id = $request->indikator_skp_id;

        $periode = PeriodeSkp::where('id', '=', $skp_id)->where('created_by', '=', $username)->count();
        if (!$periode) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'SKP not found.',
             ], 404);
         }


        $indikator = IndikatorSkp::where('id', '=', $indikator_skp_id)->where('created_by', '=', $username)->count();
        if (!$indikator) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Indikator not found.',
             ], 404);
         }

        $langkah_skp = LangkahSkp::where('created_by', '=', $username)
                                ->where('skp_id', '=', $skp_id)
                                ->where('indikator_skp_id', '=', $indikator_skp_id)
                                ->get();
       
        $langkah_skp = $langkah_skp->map(function($ss) use($request, $username) 
        {   
            return [
                "id"                   => $ss->id,
                "skp_id"               => $ss->skp_id,
                "indikator_skp_id"     => $ss->indikator_skp_id,
                "langkah"              => $ss->langkah,
                "target_tw1"           => $ss->target_tw1,
                "target_tw2"           => $ss->target_tw2,
                "target_tw3"           => $ss->target_tw3,
                "target_tw4"           => $ss->target_tw4,
                "target_tw4"           => $ss->target_tw4,
                "realisasi_tw1"         => $ss->realisasi_tw1,
                "realisasi_tw2"         => $ss->realisasi_tw3,
                "realisasi_tw3"         => $ss->realisasi_tw3,
                "realisasi_tw4"         => $ss->realisasi_tw4,
                "capaian_tw1"           => $ss->capaian_tw1,
                "capaian_tw2"           => $ss->capaian_tw2,
                "capaian_tw3"           => $ss->capaian_tw3,
                "capaian_tw4"           => $ss->capaian_tw4,
                "satuan"                => $ss->satuan,
                "keterangan"            => $ss->keterangan
            ];
        });               


        return response()->json([
            'success' => true,
            'message' => 'Daftar Rerncana Aksi SKP Pegawai  '.$username.' ',
            'data' => $langkah_skp,
        ]);
    }


     public function update_realisasi($id, Request $request)
    {
        try {

            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;
            

             // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Langkah SKP not Found',
                ], 422);
            }
             // cek existing rkpd
            $Langkah = LangkahSkp::find($id);
            if (!$Langkah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Langkah SKP not found sdsd.',
                ], 404);
            }

            /* cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->sasaran_skp_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran SKP not Found',
                ], 422);
            }
             // cek existing rkpd
            $sasaran = SasaranSkp::find($request->sasaran_skp_id);
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

             if(!Str::isUuid($request->indikator_skp_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator SKP not Found',
                ], 422);
            }

            $Indikator = IndikatorSkp::find($request->indikator_skp_id);
            if (!$Indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator SKP not found.',
                ], 404);
            }           */

            $target_tw1= (int) $Langkah->target_tw1;
            $realisasi_tw1= (int) $request->realisasi_tw1;
            $capaian_tw1 = ($target_tw1==0) ? 0 : $realisasi_tw1/$target_tw1 * 100; 
            
            $target_tw2= (int) $Langkah->target_tw2;
            $realisasi_tw2= (int) $request->realisasi_tw2;
            $capaian_tw2 = ($target_tw2==0) ? 0 : $realisasi_tw2/$target_tw2 * 100; 

            $target_tw3= (int) $Langkah->target_tw3;
            $realisasi_tw3= (int) $request->realisasi_tw3;
            $capaian_tw3 = ($target_tw3==0) ? 0 : $realisasi_tw3/$target_tw3 * 100; 

            $target_tw4= (int) $Langkah->target_tw4;
            $realisasi_tw4= (int) $request->realisasi_tw4;
            $capaian_tw4 = ($target_tw4==0) ? 0 : $realisasi_tw4/$target_tw4 * 100; 

            //validasi payload
            $form = $request->validate([
                "realisasi_tw1"    => "required",
                "realisasi_tw2"    => "required",
                "realisasi_tw3"    => "required",
                "realisasi_tw4"    => "required"
            ]);
            
            $form['updated_by'] = $request->attributes->get('payload')->username;

            $form['capaian_tw1'] = $capaian_tw1;
            $form['capaian_tw2'] = $capaian_tw2;
            $form['capaian_tw3'] = $capaian_tw3;
            $form['capaian_tw4'] = $capaian_tw4;

            $update = LangkahSkp::where('id', '=', $id)
            ->where('created_by', '=',$username)              
            ->update($form);


            return response()->json([
                'success' => true,
                'message' => ' Langkah SKP updated successfully.',
                'data' => $form,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

}

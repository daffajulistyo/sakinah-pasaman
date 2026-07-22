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
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\OPD\LangkahSkp;

use Illuminate\Support\Collection;


class IndikatorSkpController extends Controller
{

    public function getSasaranPegawai(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        
        $nip = $request->attributes->get('payload')->nip;
        $jns_jbtn_id = $request->attributes->get('payload')->jns_jbtn_id;
        $jabatan_id = $request->attributes->get('payload')->jabatan_id;

        // cek existing opd
        $opd = MasterOpd::find($master_opd_id);
        if (!$opd) {
            // jika data tidak ditamukan di dalam database
            return response()->json([
                'success' => false,
                'message' => 'Perangkat Daerah not found.',
            ], 404);
        } 

       $tujuan = BaseController::getTujuanOPD($master_opd_id);

       $sasaran = SasaranOpd::whereIn('tujuan_opd_id', $tujuan)
                ->join('pengampu_indikator_opd', 'sasaran_opd.id', '=', 'pengampu_indikator_opd.sasaran_opd_id')
                ->where('pengampu_indikator_opd.nip', '=', $nip)
                ->where('pengampu_indikator_opd.jabatan_id', '=', $jabatan_id)
                ->where('pengampu_indikator_opd.deleted_at', NULL)
                ->where('sasaran_opd.deleted_at', NULL)
                ->orderBy('sasaran_opd.order', 'ASC')
                ->distinct()
                ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'sasaran_opd.order', 'sasaran_opd.tujuan_opd_id']);

                $sasaran = $sasaran->map(function($item) use ($request)
                {      
                    
                    $master_opd_id = $request->attributes->get('payload')->opd->id;
                    $nip = $request->attributes->get('payload')->nip;
                    $jns_jbtn_id = $request->attributes->get('payload')->jns_jbtn_id;
                    $jabatan_id = $request->attributes->get('payload')->jabatan_id;
                    $username = $request->attributes->get('payload')->username;

                    
                
                    $indikator_sasaran = IndikatorOpd::join('pengampu_indikator_opd', 'indikator_opd.id', '=',  'pengampu_indikator_opd.indikator_opd_id')
                                        ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                        ->where('indikator_opd.master_opd_id', $master_opd_id)  
                                        ->where('pengampu_indikator_opd.nip', $nip)  
                                        ->where('pengampu_indikator_opd.jabatan_id', $jabatan_id)  
                                        ->where('pengampu_indikator_opd.deleted_at', NULL)
                                        ->where('indikator_opd.deleted_at', NULL)
                                        ->orderBy('indikator_opd.order', 'ASC')
                                        ->select('indikator_opd.id','indikator_opd.indikator', 'indikator_opd.order')
                                        ->distinct()
                                        ->get();

                    $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
                    {  
                        
                        return [
                            "id" => $is->id,
                            "indikator" => $is->indikator,
                            "order" => $is->order
                        ];                  
                    });
                    
                    return [
                        "id" => $item->id,
                        "tujuan_opd_id" => $item->tujuan_opd_id,
                        "sasaran" => $item->sasaran,
                        "order" => $item->order,
                        "indikator_sasaran" => $indikator_sasaran
                    ];
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Daftar Target Perjanjian Kinerja Tahun '.$request->tahun.'  ',
                    'data' => $sasaran,
                ]);   

    }
   
    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;
            $skp_id = $request->skp_id;
            $sasaran_opd_id = $request->sasaran_opd_id;
            $indikator_opd_id = $request->indikator_opd_id;
            
            

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

            /*-----------------Cek Sasaran OPD---------------------------*/
            if(!Str::isUuid($request->sasaran_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran SKP not Found',
                ], 422);
            }

            $sasaran = SasaranOpd::find($request->sasaran_opd_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran SKP not found.',
                ], 404);
            }
            /*-----------------Cek Sasaran OPD---------------------------*/

            /*-----------------Cek Indikator OPD---------------------------*/
            if(!Str::isUuid($request->indikator_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator OPD not Found',
                ], 422);
            }

            $indikator = IndikatorOpd::find($request->indikator_opd_id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator OPD not found.',
                ], 404);
            }
            /*-----------------Cek Indikator OPD---------------------------*/
            

             // cek existing target
            $cek_indikator = IndikatorSkp::where('skp_id', '=', $request->skp_id)
                      ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                      ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                      ->where('created_by', '=', $username)
                      ->get();

            //validasi payload
            $rules = [
                'satuan' => 'required',
            ];

            if($request->target){
                 $rules['target'] = 'required|numeric';
            }

            if($request->target_tw1){
                 $rules['target_tw1'] = 'required|numeric';
            }

            if($request->target_tw2){
                 $rules['target_tw2'] = 'required|numeric';
            }

            if($request->target_tw3){
                 $rules['target_tw3'] = 'required|numeric';
            }

            if($request->target_tw4){
                 $rules['target_tw4'] = 'required|numeric';
            }

            $form = $request->validate($rules);

            

            if (count($cek_indikator) <= 0) 
            {
                $form['skp_id'] = $request->skp_id;
                $form['sasaran_opd_id'] = $request->sasaran_opd_id;
                $form['indikator_opd_id'] = $request->indikator_opd_id;
                $form['satuan'] = $request->satuan;
                $form['target'] = $request->target;
                $form['target_tw1'] = $request->target_tw1;
                $form['target_tw2'] = $request->target_tw2;
                $form['target_tw3'] = $request->target_tw3;
                $form['target_tw4'] = $request->target_tw4;

                $form['id'] = Str::uuid();
                $form['created_by'] = $request->attributes->get('payload')->username;           
            
                // insert into table db
                $data = IndikatorSkp::create($form);
                
            }
            else
            {
                $form['target'] = $request->target_tw4;
                $form['target_tw1'] = $request->target_tw1;
                $form['target_tw2'] = $request->target_tw2;
                $form['target_tw3'] = $request->target_tw3;
                $form['target_tw4'] = $request->target_tw4;
                $form['satuan'] = $request->satuan;

                $update = IndikatorSkp::where('skp_id', '=', $request->skp_id)
                      ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                      ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                      ->where('created_by', '=', $username)
                      ->update($form);
            }

           
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Indikator SKP Pegawai.',
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


    public function update($id, Request $request)
    {
        try {

            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;

             // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, indikator SKP not Found',
                ], 422);
            }

             // cek existing rkpd
            $indikator = IndikatorSkp::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator SKP not found asd.',
                ], 404);
            }

             /*-----------------Cek Sasaran OPD---------------------------*/
            /*if(!Str::isUuid($request->sasaran_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran SKP not Found',
                ], 422);
            }

            $sasaran = SasaranOpd::find($request->sasaran_opd_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran SKP not found.',
                ], 404);
            } */
            /*-----------------Cek Sasaran OPD---------------------------*/

            /*-----------------Cek Indikator OPD---------------------------*/
            /*if(!Str::isUuid($request->indikator_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator OPD not Found',
                ], 422);
            }

            $indikator = IndikatorOpd::find($request->indikator_opd_id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator OPD not found.',
                ], 404);
            } */
            /*-----------------Cek Indikator OPD---------------------------*/

            /*if(!Str::isUuid($request->skp_id)){
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
            }*/

            $cek_indikator = IndikatorSkp::where('id', '=', $id)->where('created_by', '=', $username)->count();
            if ($cek_indikator <=0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator SKP not found 401',
                ], 404);
            }
            

           //validasi payload
            $rules = [
                'satuan' => 'required',
            ];

            if($request->target){
                 $rules['target'] = 'required|numeric';
            }

            if($request->target_tw1){
                 $rules['target_tw1'] = 'required|numeric';
            }

            if($request->target_tw2){
                 $rules['target_tw2'] = 'required|numeric';
            }

            if($request->target_tw3){
                 $rules['target_tw3'] = 'required|numeric';
            }

            if($request->target_tw4){
                 $rules['target_tw4'] = 'required|numeric';
            }

            $form = $request->validate($rules);
            
            $form['updated_by'] = $request->attributes->get('payload')->username;
            $form['satuan'] = $request->satuan;
            $form['target'] = !empty($request->target) ? $request->target : $indikator->target;
            $form['target_tw1'] = !empty($request->target_tw1) ? $request->target_tw1 : $indikator->target_tw1;
            $form['target_tw2'] = !empty($request->target_tw2) ? $request->target_tw2 : $indikator->target_tw2;
            $form['target_tw3'] = !empty($request->target_tw3) ? $request->target_tw3 : $indikator->target_tw3;
            $form['target_tw4'] = !empty($request->target_tw4) ? $request->target_tw4 : $indikator->target_tw4;

            $update = IndikatorSkp::where('id', '=', $id)
            ->where('created_by', '=',$username)              
            ->update($form);


            return response()->json([
                'success' => true,
                'message' => ' Indikator SKP updated successfully.',
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

            $cek_indikator = IndikatorSkp::where('id', '=', $id)
                        ->where('created_by', '=', $username)
                        ->get();

            if (count($cek_indikator) <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => 'Indikator tidak diampu oleh pegawai terkait.',
                ], 404);
            }

            $delete_data = IndikatorSkp::where('id', '=', $id)
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

    public function read(Request $request,$id)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $nip = $request->attributes->get('payload')->nip;
            $username = $request->attributes->get('payload')->username;


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator not Found',
                ], 422);
            }

            $skp = PeriodeSkp::where('id', '=', $request->skp_id)
                        ->where('nip', '=', $nip)
                        ->where('created_by', '=', $username)
                        ->first();  

            if(!$skp){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, SKP not Found',
                ], 422);
            }


            $indikator = IndikatorSkp::where('id', '=', $id)
                        ->where('skp_id', '=', $request->skp_id)
                        ->where('created_by', '=', $username)
                        ->first();                    
            
            $sasaran_opd = SasaranOpd::where('id', $indikator->sasaran_opd_id)->first();
            $indikator_opd = indikatorOpd::where('id', $indikator->indikator_opd_id)->first();

            $indikator_skp = new Collection([
                'id' => !empty($indikator) ? $indikator->id : '',
                'sasaran' => !empty($sasaran_opd) ? $sasaran_opd->sasaran : '',
                'indikator' => !empty($indikator_opd) ? $indikator_opd->indikator : '',
                'target_tw1' => !empty($indikator) ? $indikator->target_tw1 : '',
                'target_tw2' => !empty($indikator) ? $indikator->target_tw2 : '',
                'target_tw3' => !empty($indikator) ? $indikator->target_tw3 : '',
                'target_tw4' => !empty($indikator) ? $indikator->target_tw4 : '',
                'satuan' => !empty($satuan) ? $indikator->satuan : ''
            ]);
            
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Indikator Found.',
                'data' => $indikator_skp,
            ]);
        } catch (\Throwable $th) {
            // handle error
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    /*-------------------------------------------Realisasi Indikator SKP -----------------------------*/
     public function list(Request $request,$id)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $nip = $request->attributes->get('payload')->nip;
            $username = $request->attributes->get('payload')->username;


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, SKP not Found',
                ], 422);
            }

            $detail_skp = PeriodeSkp::where('id', '=', $id)
                        ->where('nip', '=', $nip)
                        ->where('created_by', '=', $username)
                        ->first();

            $sasaran_pegawai = IndikatorSkp::where('skp_id', '=', $id)
                             ->where('created_by', '=', $username)
                             ->select('sasaran_opd_id')
                             ->whereNull('deleted_at')
                            ->pluck('sasaran_opd_id')
                            ->toArray();

                         

            $sasaran_atasan = SasaranOpd::whereIn('id', $sasaran_pegawai)
                                ->distinct()
                                ->select('parent_id')
                                ->pluck('parent_id')                                
                                ->toArray();


            $sasaran = SasaranOpd::whereIn('id', $sasaran_atasan)->get();
           
            $sasaran = $sasaran->map(function($item) use($request, $id) 
            {
                $skp_id = $request->skp_id;
                $username = $request->attributes->get('payload')->username;

                 $sasaran_pegawai = SasaranOpd::join('skp_indikator', 'skp_indikator.sasaran_opd_id', '=', 'sasaran_opd.id')
                                ->where('skp_indikator.skp_id', '=', $id)
                                ->where('sasaran_opd.parent_id', '=', $item->id)
                                ->where('skp_indikator.created_by', '=', $username)
                                ->whereNull('skp_indikator.deleted_at')
                                ->distinct()
                                ->get(['sasaran_opd.id as id_indikator_opd', 'sasaran_opd.sasaran', 'sasaran_opd.order']);

                    $sasaran_pegawai = $sasaran_pegawai->map(function($item) use($request, $id) 
                    {
                        $username = $request->attributes->get('payload')->username;
                         $indikator_pegawai = IndikatorOpd::join('skp_indikator', 'skp_indikator.indikator_opd_id', '=', 'indikator_opd.id')
                                ->where('skp_indikator.skp_id', '=', $id)
                                ->where('skp_indikator.sasaran_opd_id', '=', $item->id_indikator_opd)
                                ->where('skp_indikator.created_by', '=', $username)
                                ->whereNull('skp_indikator.deleted_at')
                                ->distinct()
                                ->get(['indikator_opd.id as indikator_opd_id', 'indikator_opd.indikator', 'indikator_opd.order', 'skp_indikator.id', 'skp_indikator.target', 'skp_indikator.target_tw1', 'skp_indikator.target_tw2', 'skp_indikator.target_tw3', 'skp_indikator.target_tw4',  'skp_indikator.realisasi_tw1', 'skp_indikator.realisasi_tw2',  'skp_indikator.realisasi_tw3', 'skp_indikator.realisasi_tw4', 'skp_indikator.capaian_tw1', 'skp_indikator.capaian_tw2', 'skp_indikator.capaian_tw3', 'skp_indikator.capaian_tw4', 'skp_indikator.satuan', 'skp_indikator.realisasi', 'skp_indikator.hambatan', 'skp_indikator.tindak_lanjut', 'skp_indikator.capaian']);

                                  $indikator_pegawai = $indikator_pegawai->map(function($item) use($request, $id) 
                                  {

                                       $langkah = LangkahSkp::where('skp_id', '=', $id)
                                                ->where('indikator_skp_id', '=', $item->id)
                                                ->get();

                                        $langkah = $langkah->map(function($item) use($request, $id) 
                                        {
                                            return [
                                                "id"       => $item->id,
                                                "langkah"       => $item->langkah,
                                                "target_tw1"  => $item->target_tw1, 
                                                "target_tw2"  => $item->target_tw4, 
                                                "target_tw3"  => $item->target_tw3, 
                                                "target_tw4"  => $item->target_tw4, 
                                                "realisasi_tw1"  => $item->realisasi_tw1, 
                                                "realisasi_tw2"  => $item->realisasi_tw2, 
                                                "realisasi_tw3"  => $item->realisasi_tw3, 
                                                "realisasi_tw4"  => $item->realisasi_tw4, 
                                                "satuan"  => $item->satuan,
                                                "keterangan"  => $item->keterangan
                                            ];
                                        });

                                        return [
                                        "id"       => $item->id,
                                        "indikator_opd_id"       => $item->indikator_opd_id,
                                        "indikator"  => $item->indikator, 
                                        "target"  => $item->target, 
                                        "target_tw1"  => $item->target_tw1, 
                                        "target_tw2"  => $item->target_tw2, 
                                        "target_tw3"  => $item->target_tw3, 
                                        "target_tw4"  => $item->target_tw4, 
                                        "realisasi_tw1"  => $item->realisasi_tw1, 
                                        "realisasi_tw2"  => $item->realisasi_tw2, 
                                        "realisasi_tw3"  => $item->realisasi_tw3, 
                                        "realisasi_tw4"  => $item->realisasi_tw4, 
                                        "capaian"  => $item->capaian,                                         
                                        "capaian_tw1"  => $item->capaian_tw1,                                         
                                        "capaian_tw2"  => $item->capaian_tw2,                                         
                                        "capaian_tw3"  => $item->capaian_tw2,                                         
                                        "capaian_tw4"  => $item->capaian_tw4,                                         
                                        "satuan"  => $item->satuan, 
                                        "hambatan"  => $item->hambatan, 
                                        "tindak_lanjut"  => $item->tindak_lanjut, 
                                        "langkah"  => $langkah 
                                    ];
                                });   

                        return [
                            "id"        => $item->id_indikator_opd,
                            "sasaran"   => $item->sasaran,
                            "indikator" => $indikator_pegawai
                        ];
                    });    

                    return [
                        "id"       => $item->id,
                        "sasaran"  => $item->sasaran,
                        "sasaran_pegawai" => $sasaran_pegawai
                    ];
            });    
           

            $data_skp = new Collection([               
                'sasaran_atasan' => $sasaran
            ]);
            
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Skp Found.',
                'data' => $data_skp,
            ]);
        } catch (\Throwable $th) {
            // handle error
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    public function realisasi($id, Request $request)
    {
        try {

            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;

             // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, indikator SKP not Found',
                ], 422);
            }

             // cek existing rkpd
            $indikator = IndikatorSkp::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator SKP not found.',
                ], 404);
            }

            $cek = IndikatorSkp::where('id', '=', $id)->where('created_by', '=', $username)->count();
            if ($cek <=0 ) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator SKP Bukan Diampu Oleh Pegawai.',
                ], 404);
            }

             /*-----------------Cek Sasaran OPD---------------------------*/
            /*if(!Str::isUuid($request->sasaran_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran SKP not Found',
                ], 422);
            }

            $sasaran = SasaranOpd::find($request->sasaran_opd_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran SKP not found.',
                ], 404);
            }
            /*-----------------Cek Sasaran OPD---------------------------*/

            /*-----------------Cek Indikator OPD---------------------------
            if(!Str::isUuid($request->indikator_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator OPD not Found',
                ], 422);
            }

            $indikator = IndikatorOpd::find($request->indikator_opd_id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator OPD not found.',
                ], 404);
            }
            /*-----------------Cek Indikator OPD---------------------------*/

            /*if(!Str::isUuid($request->skp_id)){
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
            }*/

             //validasi payload
            $rules = [];

            if($request->realisasi){
                 $rules['realisasi'] = 'required|numeric';
            }

            if($request->realisasi_tw1){
                 $rules['realisasi_tw1'] = 'required|numeric';
            }

            if($request->realisasi_tw2){
                 $rules['realisasi_tw2'] = 'required|numeric';
            }

            if($request->realisasi_tw2){
                 $rules['realisasi_tw2'] = 'required|numeric';
            }

            if($request->realisasi_tw3){
                 $rules['realisasi_tw3'] = 'required|numeric';
            }

            $form = $request->validate($rules);

            $target    = (int) $indikator->target;
            $realisasi = (int) $request->realisasi;
            $capaian = ($target==0) ? 0 : $realisasi/$target * 100;      

            $target_tw1    = (int) $indikator->target_tw1;
            $realisasi_tw1 = (int) $request->realisasi_tw1;
            $capaian_tw1 = ($target_tw1==0) ? 0 : $realisasi_tw1/$target_tw1 * 100; 
            
            $target_tw2    = (int) $indikator->target_tw2;
            $realisasi_tw2 = (int) $request->realisasi_tw2;
            $capaian_tw2 = ($target_tw2==0) ? 0 : $realisasi_tw2/$target_tw2 * 100; 

            $target_tw3    = (int) $indikator->target_tw3;
            $realisasi_tw3 = (int) $request->realisasi_tw3;
            $capaian_tw3 = ($target_tw3==0) ? 0 : $realisasi_tw3/$target_tw3 * 100; 

            $target_tw4    = (int) $indikator->target_tw4;
            $realisasi_tw4 = (int) $request->realisasi_tw4;
            $capaian_tw4 = ($target_tw4==0) ? 0 : $realisasi_tw4/$target_tw4 * 100; 
            
            $form['updated_by'] = $request->attributes->get('payload')->username;
            $form['hambatan'] = $request->hambatan;
            $form['tindak_lanjut'] = $request->tindak_lanjut;
            $form['capaian'] = $capaian;
            $form['capaian_tw1'] = $capaian_tw1;
            $form['capaian_tw2'] = $capaian_tw3;
            $form['capaian_tw3'] = $capaian_tw3;
            $form['capaian_tw4'] = $capaian_tw4;

            $update = IndikatorSkp::where('id', '=', $id)
            ->where('created_by', '=',$username)              
            ->update($form);


            return response()->json([
                'success' => true,
                'message' => ' Realisasi Indikator SKP updated successfully.',
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
    /*-------------------------------------------Realisasi Indikator SKP -----------------------------*/
}

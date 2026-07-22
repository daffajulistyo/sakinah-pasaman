<?php

namespace App\Http\Controllers\Api\v1\Pegawai;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\OPD\Rencana;
use App\Models\Sakip\OPD\RencanaLangkah;
use App\Models\Sakip\OPD\Pengampu;

use App\Models\Sakip\OPD\PerjanjianKinerja;
use App\Models\Sakip\OPD\PerjanjianKinerjaProgram;
use App\Models\Sakip\Services\UserSimpeg;
use Barryvdh\DomPDF\Facade\Pdf;

class RealisasiController extends Controller
{
    public function update($id, Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;
            $jabatan_id = $request->attributes->get('payload')->jabatan_id;


            $detail = Rencana::find($id);
            if (!$detail) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Rencana Aksi not found.',
                ], 404);
            }

                 // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->sasaran_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, PerjanjianKinerja not Found',
                ], 422);
            }
            // cek existing sasaran
            $sasaran = SasaranOpd::find($request->sasaran_opd_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran not found.',
                ], 404);
            }


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->indikator_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator not Found',
                ], 422);
            }
            // cek existing indikator
            $indikator = IndikatorOpd::find($request->indikator_opd_id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }


           $cek_pengampu = Pengampu::where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                        ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                        ->where('nip', '=', $nip)
                        ->where('jabatan_id', '=', $jabatan_id)
                        ->count();

            if ($cek_pengampu <= 0) {
            // jika data tidak ditamukan di dalam database
            return response()->json([
            'success' => false,
            'message' => 'indikator tidak diampu oleh pegawai terkait.',
            ], 404);
            }

            // cek existing target
            $target = Rencana::where('tahun', '=', $request->tahun)
                      ->where('sasaran_opd_id', '=',$request->sasaran_opd_id)
                      ->where('indikator_opd_id', '=',$request->indikator_opd_id)                      
                      ->where('master_opd_id', '=',$master_opd_id)     
                      ->where('created_by', '=', $username)                     
                      ->get();

            
               //validasi payload
            $form = $request->validate([                
                "realisasi_tw1" => "required",
                "realisasi_tw2" => "required",
                "realisasi_tw3" => "required",
                "realisasi_tw4" => "required"
            ]);

            $target_tw1 = $detail->target_tw1;
            $target_tw2 = $detail->target_tw2;
            $target_tw3 = $detail->target_tw3;
            $target_tw4 = $detail->target_tw4;

            $capaian_tw1 = !empty($target_tw1) ? ($request->realisasi_tw1/ $target_tw1) * 100 : '0';
            $capaian_tw2 = !empty($target_tw2) ? ($request->realisasi_tw2/ $target_tw2) * 100 : '0';
            $capaian_tw3 = !empty($target_tw3) ? ($request->realisasi_tw3/ $target_tw3) * 100 : '0';
            $capaian_tw4 = !empty($target_tw4) ? ($request->realisasi_tw4/ $target_tw4) * 100 : '0';
            
            $form['realisasi_tw1'] = $request->realisasi_tw1;
            $form['realisasi_tw2'] = $request->realisasi_tw2;
            $form['realisasi_tw3'] = $request->realisasi_tw3;
            $form['realisasi_tw4'] = $request->realisasi_tw4;

            $form['capaian_tw1'] = $capaian_tw1;
            $form['capaian_tw2'] = $capaian_tw2;
            $form['capaian_tw3'] = $capaian_tw3;
            $form['capaian_tw4'] = $capaian_tw4;

            $form['hambatan'] = $request->hambatan;
            $form['tindak_lanjut'] = $request->tindak_lanjut;

            $update = Rencana::where('id', '=', $id)
            ->where('sasaran_opd_id', '=',$request->sasaran_opd_id)
            ->where('indikator_opd_id', '=',$request->indikator_opd_id)                
            ->where('master_opd_id', '=',$master_opd_id)  
            ->where('created_by', '=', $username)                  
            ->update($form);
            

           
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Target Rencana Aksi.',
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

    public function list(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $username = $request->attributes->get('payload')->username;
        $nip = $request->attributes->get('payload')->nip;
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

       
        $sasaran = SasaranOpd::join('pk_opd', 'pk_opd.sasaran_opd_id', '=', 'sasaran_opd.id')
                        ->join('pengampu_indikator_opd', 'sasaran_opd.id', '=', 'pengampu_indikator_opd.sasaran_opd_id')
                        ->join('tujuan_opd', 'tujuan_opd.id', '=', 'sasaran_opd.tujuan_opd_id')
                        ->whereIn('tujuan_opd_id', $tujuan)                        
                        ->where('pk_opd.tahun', $request->tahun)
                        ->where('pk_opd.created_by', $username)
                        ->orderBy('tujuan_opd.order', 'asc')
                        ->distinct()
                        ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'tujuan_opd.order', 'sasaran_opd.tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->attributes->get('payload')->opd->id;

            $indikator_sasaran = IndikatorOpd::join('pk_opd', 'pk_opd.indikator_opd_id', '=', 'indikator_opd.id') 
                                ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                ->where('pk_opd.master_opd_id', $master_opd_id) 
                                ->where('pk_opd.tahun', $request->tahun) 
                                ->select('indikator_opd.id','indikator_opd.indikator', 'indikator_opd.order')
                                ->distinct()
                                ->get(); 

            $anggaran_murni = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('murni', '=',true)
            ->sum('anggaran');

            $anggaran_perubahan = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('murni', '=',false)
            ->sum('anggaran');

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->attributes->get('payload')->opd->id;
                $username = $request->attributes->get('payload')->username;


                $rencana_aksi = Rencana::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)
                ->select('id','target_tw1','target_tw2', 'target_tw3', 'target_tw4', 'realisasi_tw1', 'realisasi_tw2', 'realisasi_tw3','realisasi_tw4', 'capaian_tw1', 'capaian_tw2', 'capaian_tw3', 'capaian_tw4', 'hambatan', 'tindak_lanjut')                

                ->get()
                ->first();


                $langkah = RencanaLangkah::where('indikator_opd_id', '=', $is->id)
                               ->where('tahun', '=', $request->tahun)
                               ->where('master_opd_id', '=', $master_opd_id)
                               ->where('created_by', '=', $username)
                               ->get();  

                $langkah = $langkah->map(function($dl) use($request) 
                {
                    return [
                        "id"            => $dl->id,
                        "langkah"       => $dl->langkah,
                        "target_tw1"       => $dl->target_tw1,
                        "target_tw2"       => $dl->target_tw2,
                        "target_tw3"       => $dl->target_tw3,
                        "target_tw4"       => $dl->target_tw4,
                        "realisasi_tw1"    => $dl->realisasi_tw1,
                        "realisasi_tw2"    => $dl->realisasi_tw2,
                        "realisasi_tw3"    => $dl->realisasi_tw3,
                        "realisasi_tw4"    => $dl->realisasi_tw4,
                        "capaian_tw1"      => $dl->capaian_tw1,
                        "capaian_tw2"      => $dl->capaian_tw2,
                        "capaian_tw3"      => $dl->capaian_tw3,
                        "capaian_tw4"      => $dl->capaian_tw4,
                        "tahun"       => $dl->tahun
                    ];
                });                

                $target_pk = PerjanjianKinerja::where('indikator_opd_id', '=', $is->id)
                ->where('tahun', '=',$request->tahun)
                ->where('master_opd_id', '=',$request->master_opd_id)
                ->get(['target', 'tahun', 'murni']); 
                
                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "rencana_aksi"=> $rencana_aksi,
                    "langkah" => $langkah,
                    "target_perjanjian_kinerja" => $target_pk
                ];                  
            }); 

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran_perjanjian_kinerja" => ['murni'=> $anggaran_murni, 'perubahan'=>$anggaran_perubahan],
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Target & Realisasi Rencana Aksi  '.$username.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }


     public function generate_pdf(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $username = $request->attributes->get('payload')->username;
        $nip = $request->attributes->get('payload')->nip;
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

       
        $sasaran = SasaranOpd::join('pk_opd', 'pk_opd.sasaran_opd_id', '=', 'sasaran_opd.id')
                        ->join('pengampu_indikator_opd', 'sasaran_opd.id', '=', 'pengampu_indikator_opd.sasaran_opd_id')
                        ->join('tujuan_opd', 'tujuan_opd.id', '=', 'sasaran_opd.tujuan_opd_id')
                        ->whereIn('tujuan_opd_id', $tujuan)                        
                        ->where('pk_opd.tahun', $request->tahun)
                        ->where('pk_opd.created_by', $username)
                        ->orderBy('tujuan_opd.order', 'asc')
                        ->distinct()
                        ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'tujuan_opd.order', 'sasaran_opd.tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->attributes->get('payload')->opd->id;

            $indikator_sasaran = IndikatorOpd::join('pk_opd', 'pk_opd.indikator_opd_id', '=', 'indikator_opd.id') 
                                ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                ->where('pk_opd.master_opd_id', $master_opd_id) 
                                ->where('pk_opd.tahun', $request->tahun) 
                                ->select('indikator_opd.id','indikator_opd.indikator', 'indikator_opd.order')
                                ->distinct()
                                ->get(); 

            $anggaran_murni = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('murni', '=',true)
            ->sum('anggaran');

            $anggaran_perubahan = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('murni', '=',false)
            ->sum('anggaran');

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->attributes->get('payload')->opd->id;
                $username = $request->attributes->get('payload')->username;


                $rencana_aksi = Rencana::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)
                ->select('id','target_tw1','target_tw2', 'target_tw3', 'target_tw4', 'realisasi_tw1', 'realisasi_tw2', 'realisasi_tw3','realisasi_tw4', 'capaian_tw1', 'capaian_tw2', 'capaian_tw3', 'capaian_tw4', 'hambatan', 'tindak_lanjut')                

                ->get()
                ->first();


                $langkah = RencanaLangkah::where('indikator_opd_id', '=', $is->id)
                               ->where('tahun', '=', $request->tahun)
                               ->where('master_opd_id', '=', $master_opd_id)
                               ->where('created_by', '=', $username)
                               ->get();  

                $langkah = $langkah->map(function($dl) use($request) 
                {
                    return [
                        "id"            => $dl->id,
                        "langkah"       => $dl->langkah,
                        "target_tw1"       => $dl->target_tw1,
                        "target_tw2"       => $dl->target_tw2,
                        "target_tw3"       => $dl->target_tw3,
                        "target_tw4"       => $dl->target_tw4,
                        "realisasi_tw1"    => $dl->realisasi_tw1,
                        "realisasi_tw2"    => $dl->realisasi_tw2,
                        "realisasi_tw3"    => $dl->realisasi_tw3,
                        "realisasi_tw4"    => $dl->realisasi_tw4,
                        "capaian_tw1"      => $dl->capaian_tw1,
                        "capaian_tw2"      => $dl->capaian_tw2,
                        "capaian_tw3"      => $dl->capaian_tw3,
                        "capaian_tw4"      => $dl->capaian_tw4,
                        "tahun"       => $dl->tahun
                    ];
                });                

                $target_pk = PerjanjianKinerja::where('indikator_opd_id', '=', $is->id)
                ->where('tahun', '=',$request->tahun)
                ->where('master_opd_id', '=',$request->master_opd_id)
                ->get(['target', 'tahun', 'murni']); 
                
                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "rencana_aksi"=> $rencana_aksi,
                    "langkah" => $langkah,
                    "target_perjanjian_kinerja" => $target_pk
                ];                  
            }); 

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran_perjanjian_kinerja" => ['murni'=> $anggaran_murni, 'perubahan'=>$anggaran_perubahan],
            ];
        });

         $profile = UserSimpeg::join('users', 'users.id', '=', 'user_simpeg.user_id')
                            ->where('nip', '=', $nip)->first();

        $data = [
            'generated_at' => now()->toDateTimeString(),
            'profil' => $profile,
            'tahun' => $request->tahun,
            'sasaran' => $sasaran
        ];
        $pdf = Pdf::loadView('report_template.pegawai.realisasi_pegawai', compact('data'))
                    ->setPaper('Legal', 'landscape');
        return $pdf->download('Realisasi'.str_replace($nip,' ','_').$request->tahun.'.pdf');
    }

}

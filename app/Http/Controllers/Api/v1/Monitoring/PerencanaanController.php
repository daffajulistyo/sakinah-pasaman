<?php

namespace App\Http\Controllers\Api\v1\Monitoring;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\OPD\Renja;
use App\Models\Sakip\OPD\RenjaProgram;
use App\Models\Sakip\OPD\PerjanjianKinerja;
use App\Models\Sakip\OPD\PerjanjianKinerjaProgram;
use App\Models\Sakip\OPD\Rencana;
use App\Models\Sakip\OPD\RencanaLangkah;

use Barryvdh\DomPDF\Facade\Pdf;

class PerencanaanController extends Controller
{
    /*---------------------------Renja------------------------------------------*/
    public function renja(Request $request)
    {
        
        $master_opd_id = $request->master_opd_id;
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
                        ->where('parent_id', '=', 0)
                        ->where('master_opd_id', '=', $master_opd_id)
                        ->distinct()
                        ->get(['id', 'sasaran', 'order', 'parent_id', 'tujuan_opd_id']);

      

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->master_opd_id;

            $anggaran = RenjaProgram::where('sasaran_opd_id', '=', $item->id)
                            ->where('master_opd_id', '=',  $master_opd_id)
                            ->where('tahun', '=',          $request->tahun)
                            ->where('murni', '=',       $request->murni)
                            ->sum('anggaran');

            $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                ->where('master_opd_id', $master_opd_id) 
                                ->select('id','indikator', 'order')
                                ->distinct()
                                ->get();

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->master_opd_id;

                $renja = Renja::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)            
                ->where('murni', $request->murni)            
                ->get()
                ->first();

                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "renja"=> $renja
                ];                  
            }); 

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran" => $anggaran
            ];
        });

        /*        
        $rawActions = SasaranOPD::with(
                        [
                            'indikator_sasaran' =>
                            function($query) use ($request) {
                                $master_opd_id = $request->master_opd_id;
                                $query->where('is_active', true);                 
                                $query->where('master_opd_id', $master_opd_id);   
                                
                                $query->with([
                                    'renja' =>
                                    function($query) use ($request) {
                                        $master_opd_id = $request->master_opd_id;

                                        $query->where('master_opd_id', $master_opd_id);                                                   
                                        $query->where('tahun', $request->tahun);                                                   
                                        $query->where('murni', $request->murni);                                                   
                                    }   
                                ]);  
                            }
                        , 
                        'anggaran_renja'=>
                        function($query) use ($request) {
                            //$master_opd_id = $request->master_opd_id;                           
                            $query->select(['sasaran_opd_id', 'anggaran']);                                                        
                            $query->get();
                            $query->first();
                        }
                    ]
                    )
                      ->whereIn('tujuan_opd_id', $tujuan)
                      ->get();
            */
        
        $periode = ($request->murni=="true") ? 'Murni' : 'Perubahan';
        return response()->json([
            'success' => true,
            'message' => 'Daftar Target Renja  '.$periode.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

    /*----------------------------Renja-----------------------------------------*/

    /*----------------------------Perjanjian Kinerja-----------------------------------------*/
    public function perjanjian_kinerja(Request $request)
    {
        $master_opd_id = $request->master_opd_id;

        $eselon = $request->eselon;

        if($eselon=="II") 
            $level =0;
        else if($eselon=="III")
            $level =1;
        else
            $level =2;


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
                        ->where('level', $level)
                        ->where('parent_id', '=', 0)
                        ->orderBy('order', 'ASC')
                        ->distinct()
                        ->get(['id', 'sasaran', 'order', 'tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->master_opd_id;
            $anggaran = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
                            ->where('master_opd_id', '=',  $master_opd_id)
                            ->where('tahun', '=',          $request->tahun)
                            ->where('murni', '=',       $request->murni)
                            ->sum('anggaran');

         
            $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                ->where('master_opd_id', $master_opd_id)                                 
                                ->select('id','indikator', 'order')
                                ->orderBy('order', 'ASC')
                                ->distinct()
                                ->get();

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->master_opd_id;

                $perjanjian_kinerja = PerjanjianKinerja::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)            
                ->where('murni', $request->murni) 
                ->first();

                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "perjanjian_kinerja"=> $perjanjian_kinerja
                ];                  
            });

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran" => $anggaran
            ];
        });

        $periode = ($request->murni=="true") ? 'Murni' : 'Perubahan';

        return response()->json([
            'success' => true,
            'message' => 'Daftar Target Perjanjian Kinerja Eselon II '.$opd->nama_opd.'  '.$periode.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

    public function generate_pdf_pk(Request $request)
    {
        $master_opd_id = $request->master_opd_id;

        $eselon = $request->eselon;

        if($eselon=="II") 
            $level =0;
        else if($eselon=="III")
            $level =1;
        else
            $level =2;


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
                        ->where('level', $level)
                        ->distinct()
                        ->get(['id', 'sasaran', 'order', 'tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->master_opd_id;
            $anggaran = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
                            ->where('master_opd_id', '=',  $master_opd_id)
                            ->where('tahun', '=',          $request->tahun)
                            ->where('murni', '=',       $request->murni)
                            ->sum('anggaran');

         
            $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                ->where('master_opd_id', $master_opd_id)                                 
                                ->select('id','indikator', 'order')
                                ->distinct()
                                ->get();

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->master_opd_id;

                $perjanjian_kinerja = PerjanjianKinerja::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)            
                ->where('murni', $request->murni) 
                ->first();

                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "perjanjian_kinerja"=> $perjanjian_kinerja
                ];                  
            });

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran" => $anggaran
            ];
        });

        $periode = ($request->murni=="true") ? 'Murni' : 'Perubahan';

         $data = [
            'generated_at' => now()->toDateTimeString(),
            'opd' => $opd,
            'tahun' => $request->tahun,
            'sasaran' => $sasaran
        ];
        $pdf = Pdf::loadView('report_template.pk_opd', compact('data'));
        return $pdf->download('PerjanjianKinerja_'.str_replace($opd->nama_opd,' ','_').$request->tahun.'.pdf');
    }
    /*----------------------------Perjanjian Kinerja-----------------------------------------*/

    /*----------------------------Rencana Aksi-----------------------------------------*/
    public function rencana_aksi(Request $request)
    {
        $master_opd_id = $request->master_opd_id;

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
                        ->whereIn('tujuan_opd_id', $tujuan)
                        ->where('parent_id', 0)
                        ->where('pk_opd.tahun', $request->tahun)
                        ->orderBy('order', 'ASC')
                        ->distinct()
                        ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'sasaran_opd.order', 'sasaran_opd.tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->master_opd_id;

            $indikator_sasaran = IndikatorOpd::join('pk_opd', 'pk_opd.indikator_opd_id', '=', 'indikator_opd.id') 
                                ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                ->where('pk_opd.master_opd_id', $master_opd_id) 
                                ->where('pk_opd.tahun', $request->tahun) 
                                ->orderBy('order', 'ASC')
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
                $master_opd_id = $request->master_opd_id;

                $rencana_aksi = Rencana::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)
                ->select('target_tw1','target_tw2', 'target_tw3', 'target_tw4')                
                ->get()
                ->first();


                $langkah = RencanaLangkah::where('indikator_opd_id', '=', $is->id)
                               ->where('tahun', '=', $request->tahun)
                               ->where('master_opd_id', '=',$master_opd_id)
                               ->get();  

                $langkah = $langkah->map(function($dl) use($request) 
                {
                    return [
                        "id"            => $dl->id,
                        "langkah"       => $dl->langkah,
                        "satuan"       => $dl->satuan,
                        "keterangan"       => $dl->keterangan,
                        "tahun"       => $dl->tahun
                    ];
                });                

                $target_pk = PerjanjianKinerja::where('indikator_opd_id', '=', $is->id)
                ->where('tahun', '=',$request->tahun)
                ->where('master_opd_id', '=', $master_opd_id)
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
            'message' => 'Daftar Target Rencana Aksi '.$opd->nama_opd.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

    public function generate_pdf_aksi(Request $request)
    {
        $master_opd_id = $request->master_opd_id;

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
                        ->whereIn('tujuan_opd_id', $tujuan)
                        ->where('parent_id', 0)
                        ->where('pk_opd.tahun', $request->tahun)
                        ->distinct()
                        ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'sasaran_opd.order', 'sasaran_opd.tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->master_opd_id;

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
                $master_opd_id = $request->master_opd_id;

                $rencana_aksi = Rencana::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)
                ->select('target_tw1','target_tw2', 'target_tw3', 'target_tw4')                
                ->get()
                ->first();


                $langkah = RencanaLangkah::where('indikator_opd_id', '=', $is->id)
                               ->where('tahun', '=', $request->tahun)
                               ->where('master_opd_id', '=',$request->master_opd_id)
                               ->get();  

                $langkah = $langkah->map(function($dl) use($request) 
                {
                    return [
                        "id"            => $dl->id,
                        "langkah"       => $dl->langkah,
                        "satuan"       => $dl->satuan,
                        "keterangan"       => $dl->keterangan,
                        "tahun"       => $dl->tahun
                    ];
                });                

                $target_pk = PerjanjianKinerja::where('indikator_opd_id', '=', $is->id)
                ->where('tahun', '=',$request->tahun)
                ->where('master_opd_id', '=', $master_opd_id)
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

         $data = [
            'generated_at' => now()->toDateTimeString(),
            'opd' => $opd,
            'tahun' => $request->tahun,
            'sasaran' => $sasaran
        ];
        $pdf = Pdf::loadView('report_template.aksi_opd', compact('data'));
        return $pdf->download('RencanaAksi'.str_replace($opd->nama_opd,' ','_').$request->tahun.'.pdf');
    }
    /*----------------------------Rencana Aksi-----------------------------------------*/

    /*----------------------------Realisasi Rencana Aksi-----------------------------------------*/
    public function realisasi(Request $request)
    {
        $master_opd_id = $request->master_opd_id;

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
                        ->whereIn('tujuan_opd_id', $tujuan)
                        ->where('parent_id', 0)
                        ->where('pk_opd.tahun', $request->tahun)
                        ->distinct()
                        ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'sasaran_opd.order', 'sasaran_opd.tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->master_opd_id;

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
                $master_opd_id = $request->master_opd_id;

                $rencana_aksi = Rencana::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)
                ->select('id','target_tw1','target_tw2', 'target_tw3', 'target_tw4', 'realisasi_tw1', 'realisasi_tw2', 'realisasi_tw3','realisasi_tw4', 'capaian_tw1', 'capaian_tw2', 'capaian_tw3', 'capaian_tw4', 'hambatan', 'tindak_lanjut')  
                ->first();


                $langkah = RencanaLangkah::where('indikator_opd_id', '=', $is->id)
                               ->where('tahun', '=', $request->tahun)
                               ->where('master_opd_id', '=', $master_opd_id)
                               ->get();  

                $langkah = $langkah->map(function($dl) use($request) 
                {
                    return [
                        "id"            => $dl->id,
                        "langkah"       => $dl->langkah,
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
            'message' => 'Daftar Target & Realisasi Rencana Aksi  '.$opd->nama_opd.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

    public function generate_pdf_realisasi(Request $request)
    {
        try{
            $master_opd_id = $request->master_opd_id;

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
                            ->whereIn('tujuan_opd_id', $tujuan)
                            ->where('parent_id', 0)
                            ->where('pk_opd.tahun', $request->tahun)
                            ->distinct()
                            ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'sasaran_opd.order', 'sasaran_opd.tujuan_opd_id']);

            $sasaran = $sasaran->map(function($item) use ($request)
            {    
                $master_opd_id = $request->master_opd_id;

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
                    $master_opd_id = $request->master_opd_id;

                    $rencana_aksi = Rencana::where('indikator_opd_id', $is->id) 
                    ->where('master_opd_id', $master_opd_id) 
                    ->where('tahun', $request->tahun)
                    ->select('id','target_tw1','target_tw2', 'target_tw3', 'target_tw4', 'realisasi_tw1', 'realisasi_tw2', 'realisasi_tw3','realisasi_tw4', 'capaian_tw1', 'capaian_tw2', 'capaian_tw3', 'capaian_tw4', 'hambatan', 'tindak_lanjut')  
                    ->first();


                    $langkah = RencanaLangkah::where('indikator_opd_id', '=', $is->id)
                                ->where('tahun', '=', $request->tahun)
                                ->where('master_opd_id', '=', $master_opd_id)
                                ->get();  

                    $langkah = $langkah->map(function($dl) use($request) 
                    {
                        return [
                            "id"            => $dl->id,
                            "langkah"       => $dl->langkah,
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
            // dd(json_encode(json_decode($sasaran)));
            $data = [
                'generated_at' => now()->toDateTimeString(),
                'opd' => $opd,
                'realisasi_renaksi' => $sasaran
            ];
            $pdf = Pdf::loadView('report_template.realisasi_renaksi_opd', compact('data'))
                    ->setPaper('A4', 'landscape');
            return $pdf->download('IKU_'.str_replace($opd->nama_opd,' ','_').'.pdf');
        }
        catch (\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }
    /*----------------------------Realisasi Rencana Aksi-----------------------------------------*/

}

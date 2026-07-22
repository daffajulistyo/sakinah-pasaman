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
use App\Models\Sakip\OPD\PerjanjianKinerja;
use App\Models\Sakip\OPD\PerjanjianKinerjaProgram;
use App\Models\Sakip\OPD\Pengampu;

use App\Models\Sakip\Services\UserSimpeg;
use Barryvdh\DomPDF\Facade\Pdf;

class RencanaController extends Controller
{
    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;
            $jabatan_id = $request->attributes->get('payload')->jabatan_id;

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
                "tahun" => "required",
                "target_tw1" => "required",
                "target_tw2" => "required",
                "target_tw3" => "required",
                "target_tw4" => "required"
            ]);

            if (count($target) <= 0) {
                $form['sasaran_opd_id'] = $request->sasaran_opd_id;
                $form['indikator_opd_id'] = $request->indikator_opd_id;
                $form['master_opd_id'] = $master_opd_id;
                $form['target_tw1'] = $request->target_tw1;
                $form['target_tw2'] = $request->target_tw2;
                $form['target_tw3'] = $request->target_tw3;
                $form['target_tw4'] = $request->target_tw4;
    
                // create uuid and assign author
                $form['id'] = Str::uuid();
                $form['created_by'] = $request->attributes->get('payload')->username;
                
                // insert into table db
                $data = Rencana::create($form);
            }
            else
            {
                $form['target_tw1'] = $request->target_tw1;
                $form['target_tw2'] = $request->target_tw2;
                $form['target_tw3'] = $request->target_tw3;
                $form['target_tw4'] = $request->target_tw4;

                $update = Rencana::where('tahun', '=', $request->tahun)
                ->where('sasaran_opd_id', '=',$request->sasaran_opd_id)
                ->where('indikator_opd_id', '=',$request->indikator_opd_id)                
                ->where('master_opd_id', '=',$master_opd_id)   
                ->where('created_by', '=', $username)              
                ->update($form);
            }

           
            
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

       
        $sasaran = SasaranOpd::join('pk_opd', 'pk_opd.sasaran_opd_id', '=', 'sasaran_opd.id')
                        ->join('pengampu_indikator_opd', 'sasaran_opd.id', '=', 'pengampu_indikator_opd.sasaran_opd_id')
                        ->join('tujuan_opd', 'tujuan_opd.id', '=', 'sasaran_opd.tujuan_opd_id')
                        ->whereIn('tujuan_opd_id', $tujuan)                        
                        ->where('pk_opd.tahun', $request->tahun)
                        ->where('pk_opd.created_by', $username)
                        ->where('pengampu_indikator_opd.nip', '=', $nip)
                        ->where('pengampu_indikator_opd.jabatan_id', '=', $jabatan_id)
                        ->orderBy('tujuan_opd.order', 'asc')
                        ->distinct()
                        ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'tujuan_opd.order', 'sasaran_opd.tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $username = $request->attributes->get('payload')->username;

            $indikator_sasaran = IndikatorOpd::join('pk_opd', 'pk_opd.indikator_opd_id', '=', 'indikator_opd.id') 
                                ->join('pengampu_indikator_opd', 'indikator_opd.id', '=', 'pengampu_indikator_opd.indikator_opd_id')
                                ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                ->where('pk_opd.master_opd_id', $master_opd_id) 
                                ->where('pk_opd.tahun', $request->tahun) 
                                ->where('pk_opd.created_by', $username)
                                ->select('indikator_opd.id','indikator_opd.indikator', 'indikator_opd.order')
                                ->distinct()
                                ->get(); 

            $anggaran_murni = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('created_by', $username) 
            ->where('murni', '=',true)
            ->sum('anggaran');

            $anggaran_perubahan = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('created_by', $username) 
            ->where('murni', '=',false)
            ->sum('anggaran');

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->attributes->get('payload')->opd->id;

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
            'message' => 'Daftar Target Rencana Aksi Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

     public function generate_pdf(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $username = $request->attributes->get('payload')->username;

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

       
        $sasaran = SasaranOpd::join('pk_opd', 'pk_opd.sasaran_opd_id', '=', 'sasaran_opd.id')
                        ->join('pengampu_indikator_opd', 'sasaran_opd.id', '=', 'pengampu_indikator_opd.sasaran_opd_id')
                        ->join('tujuan_opd', 'tujuan_opd.id', '=', 'sasaran_opd.tujuan_opd_id')
                        ->whereIn('tujuan_opd_id', $tujuan)                        
                        ->where('pk_opd.tahun', $request->tahun)
                        ->where('pk_opd.created_by', $username)
                        ->where('pengampu_indikator_opd.nip', '=', $nip)
                        ->where('pengampu_indikator_opd.jabatan_id', '=', $jabatan_id)
                        ->orderBy('tujuan_opd.order', 'asc')
                        ->distinct()
                        ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'tujuan_opd.order', 'sasaran_opd.tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $username = $request->attributes->get('payload')->username;

            $indikator_sasaran = IndikatorOpd::join('pk_opd', 'pk_opd.indikator_opd_id', '=', 'indikator_opd.id') 
                                ->join('pengampu_indikator_opd', 'indikator_opd.id', '=', 'pengampu_indikator_opd.indikator_opd_id')
                                ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                ->where('pk_opd.master_opd_id', $master_opd_id) 
                                ->where('pk_opd.tahun', $request->tahun) 
                                ->where('pk_opd.created_by', $username)
                                ->select('indikator_opd.id','indikator_opd.indikator', 'indikator_opd.order')
                                ->distinct()
                                ->get(); 

            $anggaran_murni = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('created_by', $username) 
            ->where('murni', '=',true)
            ->sum('anggaran');

            $anggaran_perubahan = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
            ->where('tahun', '=',$request->tahun)
            ->where('master_opd_id', $master_opd_id) 
            ->where('created_by', $username) 
            ->where('murni', '=',false)
            ->sum('anggaran');

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->attributes->get('payload')->opd->id;

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
               // "anggaran_perjanjian_kinerja" => ['murni'=> $anggaran_murni, 'perubahan'=>$anggaran_perubahan],
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
                $pdf = Pdf::loadView('report_template.pegawai.aksi_pegawai', compact('data'))
                            ->setPaper('Legal', 'landscape');
                return $pdf->download('RencanaAksi'.str_replace($nip,' ','_').$request->tahun.'.pdf');
    }
}

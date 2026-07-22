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
use App\Models\Sakip\OPD\PerjanjianKinerja;
use App\Models\Sakip\OPD\PerjanjianKinerjaProgram;
use Illuminate\Support\Facades\Crypt;
use App\Models\Sakip\OPD\Pengampu;

use App\Models\Sakip\Services\UserSimpeg;
use Barryvdh\DomPDF\Facade\Pdf;

class PerjanjianController extends Controller
{
    public function showall(Request $request)
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
                ->join('tujuan_opd', 'tujuan_opd.id', '=', 'sasaran_opd.tujuan_opd_id')
                ->where('pengampu_indikator_opd.nip', '=', $nip)
                ->where('pengampu_indikator_opd.jabatan_id', '=', $jabatan_id)
                ->orderBy('tujuan_opd.order', 'asc')
                ->distinct()
                ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'tujuan_opd.order', 'sasaran_opd.tujuan_opd_id']);

                $sasaran = $sasaran->map(function($item) use ($request)
                {      
                    
                    $master_opd_id = $request->attributes->get('payload')->opd->id;
                    $nip = $request->attributes->get('payload')->nip;
                    $jns_jbtn_id = $request->attributes->get('payload')->jns_jbtn_id;
                    $jabatan_id = $request->attributes->get('payload')->jabatan_id;
                    $username = $request->attributes->get('payload')->username;

                    $anggaran = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
                                    ->where('master_opd_id', '=',  $master_opd_id)
                                    ->where('tahun', '=',          $request->tahun)
                                    ->where('murni', '=',          $request->murni)
                                 //   ->where('created_by', '=',       $username)
                                    ->sum('anggaran');

                
                    $indikator_sasaran = IndikatorOpd::join('pengampu_indikator_opd', 'indikator_opd.id', '=',  'pengampu_indikator_opd.indikator_opd_id')
                                        ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                        ->where('indikator_opd.master_opd_id', $master_opd_id)  
                                        ->where('pengampu_indikator_opd.nip', $nip)  
                                        ->where('pengampu_indikator_opd.jabatan_id', $jabatan_id)  
                                        ->orderBy('indikator_opd.order', 'ASC')
                                        ->select('indikator_opd.id','indikator_opd.indikator', 'indikator_opd.order')
                                        ->distinct()
                                        ->get();

                    $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
                    {   
                        $master_opd_id = $request->attributes->get('payload')->opd->id;

                        $username = $request->attributes->get('payload')->username;
                        $nip = $request->attributes->get('payload')->nip;
                        $jabatan_id = $request->attributes->get('payload')->jabatan_id;
                        $eselon = $request->attributes->get('payload')->eselon_id;

                        $perjanjian_kinerja = PerjanjianKinerja::where('indikator_opd_id', $is->id) 
                        ->where('master_opd_id', $master_opd_id) 
                        ->where('tahun', $request->tahun)            
                        ->where('murni', $request->murni) 
                        ->where('created_by', $username) 
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
                        "anggaran" => $anggaran,
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
            $jabatan_id = $request->attributes->get('payload')->jabatan_id;
            $eselon = $request->attributes->get('payload')->eselon_id;
            
             // cek validasi jika id berformar uuid atau tidak
             if(!Str::isUuid($request->sasaran_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
            // cek existing indikator
            $sasaran = SasaranOpd::find($request->sasaran_opd_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran not found.',
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
            $target = PerjanjianKinerja::where('tahun', '=', $request->tahun)
                      ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                      ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                      ->where('murni', '=', $request->murni)
                      ->where('master_opd_id', '=', $master_opd_id)                      
                      ->where('eselon', '=', $eselon)
                      ->where('created_by', '=', $username)
                      ->get();
             
            //validasi payload
            $form = $request->validate([
                "tahun" => "required|integer",
                "target" => "required",
                "murni" => "required|boolean"
            ]);

            if (count($target) <= 0) 
            {
               

                $form['sasaran_opd_id'] = $request->sasaran_opd_id;
                $form['indikator_opd_id'] = $request->indikator_opd_id;
                $form['master_opd_id'] = $master_opd_id;
                $form['eselon'] = $eselon;

                // create uuid and assign author
                $form['id'] = Str::uuid();
                $form['created_by'] = $request->attributes->get('payload')->username;
                
                // insert into table db
                $data = PerjanjianKinerja::create($form);
            }
            else
            {
                $form['target'] = $request->target;
                $form['eselon'] = $eselon;
                $update = PerjanjianKinerja::where('tahun', '=', $request->tahun)
                      ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                      ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                      ->where('murni', '=', $request->murni)
                      ->where('master_opd_id', '=', $master_opd_id)                      
                      ->where('eselon', '=', $eselon)
                      ->where('created_by', '=', $username)
                      ->update($form);
            }
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Target Perjanjian Kinerja.',
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


    public function createProgram(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $username = $request->attributes->get('payload')->username;
            $nip = $request->attributes->get('payload')->nip;


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->sasaran_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
            // cek existing rkpd
            $sasaran = SasaranOpd::find($request->sasaran_opd_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran not found.',
                ], 404);
            }

            //cek sasaran by opd id
            $cek_sasaran = Pengampu::where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                           ->where('master_opd_id', '=', $master_opd_id)
                           ->where('nip', '=', $nip)
                           ->count();

                if ($cek_sasaran <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran bukan diampu oleh pegawai bersangkutan',
                ], 404);
            }


            // cek existing target
            $target = PerjanjianKinerjaProgram::where('sasaran_opd_id', $request->sasaran_opd_id)
                      ->where('tahun', '=', $request->tahun)
                      ->where('murni', '=', $request->murni)
                      ->where('master_opd_id', '=', $master_opd_id)
                      ->where('created_by', '=', $username)
                      ->limit(1)
                      ->get();

                //validasi payload
            $form = $request->validate([
                "tahun" => "required",
                "anggaran" => "required",
                "list_program" => "required|json",
                "is_active" => "required|boolean",
                "murni" => "required|boolean"
            ]);

            if (count($target) <= 0) 
            {   
                $form['sasaran_opd_id'] = $request->sasaran_opd_id;
                $form['master_opd_id'] = $master_opd_id;
                $form['tahun'] = $request->tahun;
                $form['anggaran'] = $request->anggaran;
                $form['murni'] = $request->murni;

                // create uuid and assign author
                $form['id'] = Str::uuid();
                $form['created_by'] = $request->attributes->get('payload')->username;
                
                // insert into table db
                $data = PerjanjianKinerjaProgram::create($form);
            
            }
            else
            {
                $form['list_program'] = $request->list_program;
                $form['anggaran'] = $request->anggaran;

                $update = PerjanjianKinerjaProgram::where('sasaran_opd_id', $request->sasaran_opd_id)
                ->where('tahun', '=', $request->tahun)
                ->where('murni', '=', $request->murni)
                ->where('master_opd_id', '=', $master_opd_id)
                ->where('created_by', '=', $username)
                ->update($form);           
            }
                      

            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Program PK.',
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


    public function listProgram(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $username = $request->attributes->get('payload')->username;


         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }

        $sasaran_opd_id = $request->get('sasaran_opd_id');

         if(!Str::isUuid($sasaran_opd_id)){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Id, Sasaran not Found',
            ], 422);
        }

        $sasaran = SasaranOpd::find($sasaran_opd_id);
        if (!$sasaran) {
            return response()->json([
                'success' => false,
                'message' => 'Sasaran OPD not found.',
            ], 404);
        }

         //cek sasaran by opd id
         $cek_sasaran = Pengampu::where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                    ->where('master_opd_id', '=', $master_opd_id)
                    ->where('nip', '=', $username)
                    ->count();

            if ($cek_sasaran <= 0) {
            // jika data tidak ditamukan di dalam database
            return response()->json([
            'success' => false,
            'message' => 'sasaran bukan diampu oleh pegawai bersangkutan',
            ], 404);
            }


        $program_pk = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $sasaran_opd_id)
                ->where('tahun', '=', $request->tahun)
                ->where('murni', '=', $request->murni)
                ->where('created_by', '=', $username)
                ->limit(1)
                ->get();   
      
        if(count($program_pk) > 0)
        {
            $list_prog = [
                'id' => $program_pk[0]['id'],
                'sasaran_opd_id' => $program_pk[0]['sasaran_opd_id'],
                'list_program' => $program_pk[0]['list_program'],
                'tahun' => $program_pk[0]['tahun'],
                'is_active' => $program_pk[0]['is_active'],
                'murni' => $program_pk[0]['murni'],
            ];
        }
        else
        $list_prog = array();

        $sasaran = [
            'sasaran' =>  $sasaran,
            'list_program' => $list_prog
        ];

        $periode = ($request->murni=="true") ? 'Murni' : 'Perubahan';

        return response()->json([
            'success' => true,
            'message' => 'Daftar Program Perjanjian Kinerja  '.$periode.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

    public function generate_pdf(Request $request)
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
                ->join('tujuan_opd', 'tujuan_opd.id', '=', 'sasaran_opd.tujuan_opd_id')
                ->where('pengampu_indikator_opd.nip', '=', $nip)
                ->where('pengampu_indikator_opd.jabatan_id', '=', $jabatan_id)
                ->orderBy('tujuan_opd.order', 'asc')
                ->distinct()
                ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'tujuan_opd.order', 'sasaran_opd.tujuan_opd_id']);

                $sasaran = $sasaran->map(function($item) use ($request)
                {      
                    
                    $master_opd_id = $request->attributes->get('payload')->opd->id;
                    $nip = $request->attributes->get('payload')->nip;
                    $jns_jbtn_id = $request->attributes->get('payload')->jns_jbtn_id;
                    $jabatan_id = $request->attributes->get('payload')->jabatan_id;
                    $username = $request->attributes->get('payload')->username;

                    $anggaran = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
                                    ->where('master_opd_id', '=',  $master_opd_id)
                                    ->where('tahun', '=',          $request->tahun)
                                    ->where('murni', '=',          $request->murni)
                                 //   ->where('created_by', '=',       $username)
                                    ->sum('anggaran');

                
                    $indikator_sasaran = IndikatorOpd::join('pengampu_indikator_opd', 'indikator_opd.id', '=',  'pengampu_indikator_opd.indikator_opd_id')
                                        ->where('indikator_opd.sasaran_opd_id', $item->id) 
                                        ->where('indikator_opd.master_opd_id', $master_opd_id)  
                                        ->where('pengampu_indikator_opd.nip', $nip)  
                                        ->where('pengampu_indikator_opd.jabatan_id', $jabatan_id)  
                                        ->orderBy('indikator_opd.order', 'ASC')
                                        ->select('indikator_opd.id','indikator_opd.indikator', 'indikator_opd.order')
                                        ->distinct()
                                        ->get();

                    $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
                    {   
                        $master_opd_id = $request->attributes->get('payload')->opd->id;

                        $username = $request->attributes->get('payload')->username;
                        $nip = $request->attributes->get('payload')->nip;
                        $jabatan_id = $request->attributes->get('payload')->jabatan_id;
                        $eselon = $request->attributes->get('payload')->eselon_id;

                        $perjanjian_kinerja = PerjanjianKinerja::where('indikator_opd_id', $is->id) 
                        ->where('master_opd_id', $master_opd_id) 
                        ->where('tahun', $request->tahun)            
                        ->where('murni', $request->murni) 
                        ->where('created_by', $username) 
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
                        "anggaran" => $anggaran,
                        "indikator_sasaran" => $indikator_sasaran
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
                $pdf = Pdf::loadView('report_template.pegawai.pk_pegawai', compact('data'))
                            ->setPaper('A4', 'landscape');
                return $pdf->download('PerjanjianKinerja_'.str_replace($nip,' ','_').$request->tahun.'.pdf');
    }
}

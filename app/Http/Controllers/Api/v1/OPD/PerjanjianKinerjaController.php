<?php

namespace App\Http\Controllers\Api\v1\OPD;

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

use Barryvdh\DomPDF\Facade\Pdf;

class PerjanjianKinerjaController extends Controller
{   

    public function showall(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;

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

       /* $rawActions = SasaranOPD::with(
                         ['indikator_sasaran' =>
                       function($query) use ($request) {
                            $query->where('is_active', true);                                                         
                            $query->with([
                                'perjanjian_kinerja' =>
                                function($query) use ($request) {
                                    $master_opd_id = $request->attributes->get('payload')->opd->id;
                                    $query->where('master_opd_id', $master_opd_id);                                                   
                                    $query->where('tahun', $request->tahun);                                                   
                                    $query->where('murni', $request->murni);                                                                                                                                       
                                }   
                            ]);                  
                        }
                      ])
                      ->whereIn('tujuan_opd_id', $tujuan)
                      ->where('level', $level)
                      ->distinct()
                      ->get(['sasaran_opd.id', 'sasaran_opd.sasaran', 'sasaran_opd.order']);
        */
        

        $sasaran = SasaranOpd::whereIn('tujuan_opd_id', $tujuan)
                        ->where('level', $level)
                        ->where('parent_id', '=', 0)
                        ->where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->distinct()
                        ->get(['id', 'sasaran', 'order', 'tujuan_opd_id', 'is_active']);

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $anggaran = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
                            ->where('master_opd_id', '=',  $master_opd_id)
                            ->where('tahun', '=',          $request->tahun)
                            ->where('murni', '=',       $request->murni)
                            ->sum('anggaran');

         
            $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                ->where('master_opd_id', $master_opd_id)                                 
                                ->where('is_active', true)
                                ->orderBy('order', 'asc')
                                ->select('id','indikator', 'order', 'is_active')
                                ->orderBy('order', 'ASC')
                                ->distinct()
                                ->get();

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->attributes->get('payload')->opd->id;

                $perjanjian_kinerja = PerjanjianKinerja::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $request->tahun)            
                ->where('murni', $request->murni) 
                ->first();

                return [
                    "id" => $is->id,
                    "indikator" => $is->indikator,
                    "order" => $is->order,
                    "is_active" => $is->is_active,
                    "perjanjian_kinerja"=> $perjanjian_kinerja
                ];                  
            });

            return [
                "id" => $item->id,
                "tujuan_opd_id" => $item->tujuan_opd_id,
                "sasaran" => $item->sasaran,
                "order" => $item->order,
                "is_active" => $item->is_active,
                "indikator_sasaran" => $indikator_sasaran,
                "anggaran" => $anggaran
            ];
        });

        $periode = ($request->murni=="true") ? 'Murni' : 'Perubahan';

        return response()->json([
            'success' => true,
            'message' => 'Daftar Target Perjanjian Kinerja Eselon '.$eselon.'  '.$periode.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

    public function create(Request $request)
    {
        try {
            $master_opd_id = $request->attributes->get('payload')->opd->id;

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

            $cek = IndikatorOpd::where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                                ->where('id', '=', $request->indikator_opd_id)
                                ->count();

            if ($cek <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'indikator tidak diampu oleh opd terkait.',
                ], 404);
            }


            // cek existing target
            $target = PerjanjianKinerja::where('tahun', '=', $request->tahun)
                      ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                      ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                      ->where('master_opd_id', '=', $master_opd_id)
                      ->where('murni', '=', $request->murni)
                      ->where('eselon', '=', $request->eselon)
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
                $form['eselon'] = $request->eselon;

                // create uuid and assign author
                $form['id'] = Str::uuid();
                $form['created_by'] = $request->attributes->get('payload')->username;
                
                // insert into table db
                $data = PerjanjianKinerja::create($form);
            }
            else
            {
                $form['target'] = $request->target;
                $form['eselon'] = $request->eselon;
                $update = PerjanjianKinerja::where('tahun', '=', $request->tahun)
                      ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                      ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                      ->where('master_opd_id', '=', $master_opd_id)
                      ->where('murni', '=', $request->murni)
                      ->where('eselon', '=', $request->eselon)
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
            $cek_sasaran = SasaranOpd::where('id', '=', $request->sasaran_opd_id)
                           ->where('master_opd_id', '=', $master_opd_id)
                           ->count();

                if ($cek_sasaran <= 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran bukan diampu oleh opd bersangkutan',
                ], 404);
            }


            // cek existing target
            $target = PerjanjianKinerjaProgram::where('sasaran_opd_id', $request->sasaran_opd_id)
                      ->where('tahun', '=', $request->tahun)
                      ->where('murni', '=', $request->murni)
                      ->where('master_opd_id', '=', $master_opd_id)
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
                ->update($form);           
            }
                      

            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Kegiatan RKPD.',
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

         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }

       /* $tujuan = BaseController::getTujuanOPD($master_opd_id);

        $rawActions = SasaranOPD::with(
                        ['program_perjanjian_kinerja' =>
                        function($query) use ($request) {
                            $master_opd_id = $request->attributes->get('payload')->opd->id;
                            $query->where('master_opd_id', $master_opd_id);                                                   
                            $query->where('tahun', $request->tahun);                                                   
                            $query->where('murni', $request->murni);   
                            
                        }
                       ])
                      ->whereIn('tujuan_opd_id', $tujuan)
                      ->get(['id', 'sasaran', 'order', 'is_active']);
        */

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

        $program_pk = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $sasaran_opd_id)
                ->where('tahun', '=', $request->tahun)
                ->where('murni', '=', $request->murni)
                ->limit(1)
                ->get();     

        if(count($program_pk) > 0)
        {
            $list_prog = [
                'id' => $program_pk[0]['id'],
                'pohon_kinerja_sasaran_id' => $program_pk[0]['pohon_kinerja_sasaran_id'],
                'list_program' => json_decode($program_pk[0]['list_program']),
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
            'message' => 'Daftar Program Renja  '.$periode.' Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }


    public function generate_pdf(Request $request)
    {
        $master_opd_id = $request->attributes->get('payload')->opd->id;

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

        $tahun = !empty($request->tahun) ? $request->tahun : date('Y');
        $murni = !empty($request->murni) ? $request->murni : 'true';

        $tujuan = BaseController::getTujuanOPD($master_opd_id);  
        
        $sasaran = SasaranOpd::whereIn('tujuan_opd_id', $tujuan)
                        ->where('parent_id', '=', 0)
                        ->where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->distinct()
                        ->get(['id', 'sasaran', 'order', 'tujuan_opd_id']);

        $sasaran = $sasaran->map(function($item) use ($request, $tahun, $murni)
        {    
            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $anggaran = PerjanjianKinerjaProgram::where('sasaran_opd_id', '=', $item->id)
                            ->where('master_opd_id', '=',  $master_opd_id)
                            ->where('tahun', '=',        $tahun)
                            ->where('murni', '=',       $murni)
                            ->sum('anggaran');

         
            $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                ->where('master_opd_id', $master_opd_id)     
                                ->where('is_active', true)
                                ->orderBy('order', 'asc')                            
                                ->select('id','indikator', 'order')
                                ->distinct()
                                ->get();

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request, $tahun, $murni)
            {   
                $master_opd_id = $request->attributes->get('payload')->opd->id;

                $perjanjian_kinerja = PerjanjianKinerja::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $tahun)            
                ->where('murni', $murni) 
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

        $periode = ($murni=="true") ? 'Murni' : 'Perubahan';

         $data = [
            'generated_at' => now()->toDateTimeString(),
            'opd' => $opd,
            'tahun' => $tahun,
            'sasaran' => $sasaran
        ];
        $pdf = Pdf::loadView('report_template.opd.pk_opd', compact('data'))->setPaper('Legal', 'portrait');;
        return $pdf->download('PerjanjianKinerja_'.str_replace($opd->nama_opd,' ','_').$tahun.'.pdf');
    }

}

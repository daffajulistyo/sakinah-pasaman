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
use App\Models\Sakip\OPD\Renja;
use App\Models\Sakip\OPD\RenjaProgram;

use Barryvdh\DomPDF\Facade\Pdf;

class RenjaController extends Controller
{
    public function showall(Request $request)
    {
        
        $master_opd_id = $request->get('payload')->opd->id;
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
                        ->where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->distinct()
                        ->get(['id', 'sasaran', 'order', 'parent_id', 'tujuan_opd_id']);

      

        $sasaran = $sasaran->map(function($item) use ($request)
        {    
            $master_opd_id = $request->get('payload')->opd->id;

            $anggaran = RenjaProgram::where('sasaran_opd_id', '=', $item->id)
                            ->where('master_opd_id', '=',  $master_opd_id)
                            ->where('tahun', '=',          $request->tahun)
                            ->where('murni', '=',       $request->murni)
                            ->sum('anggaran');

            $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                ->where('master_opd_id', $master_opd_id) 
                                ->where('is_active', true)
                                ->orderBy('order', 'asc')
                                ->select('id','indikator', 'order')
                                ->distinct()
                                ->get();

            $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
            {   
                $master_opd_id = $request->get('payload')->opd->id;

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
                                $master_opd_id = $request->get('payload')->opd->id;
                                $query->where('is_active', true);                 
                                $query->where('master_opd_id', $master_opd_id);   
                                
                                $query->with([
                                    'renja' =>
                                    function($query) use ($request) {
                                        $master_opd_id = $request->get('payload')->opd->id;

                                        $query->where('master_opd_id', $master_opd_id);                                                   
                                        $query->where('tahun', $request->tahun);                                                   
                                        $query->where('murni', $request->murni);                                                   
                                    }   
                                ]);  
                            }
                        , 
                        'anggaran_renja'=>
                        function($query) use ($request) {
                            //$master_opd_id = $request->get('payload')->opd->id;                           
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

    public function create(Request $request)
    {
        try {
            $master_opd_id = $request->get('payload')->opd->id;

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


            // cek existing target
            $target = Renja::where('tahun', '=', $request->tahun)
                      ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                      ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                      ->where('master_opd_id', '=', $master_opd_id)
                      ->where('murni', '=', $request->murni)
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

                // create uuid and assign author
                $form['id'] = Str::uuid();
                $form['created_by'] = $request->get('payload')->username;
                
                // insert into table db
                $data = Renja::create($form);
            }
            else
            {
                $form['target'] = $request->target;
                $update = Renja::where('tahun', '=', $request->tahun)
                      ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                      ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                      ->where('master_opd_id', '=', $master_opd_id)
                      ->where('murni', '=', $request->murni)
                      ->update($form);
            }
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Target Renja.',
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

            $master_opd_id = $request->get('payload')->opd->id;

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
            $target = RenjaProgram::where('sasaran_opd_id', $request->sasaran_opd_id)
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
                $form['created_by'] = $request->get('payload')->username;
                
                // insert into table db
                $data = RenjaProgram::create($form);
            
            }
            else
            {
                $form['list_program'] = $request->list_program;
                $form['anggaran'] = $request->anggaran;

                $update = RenjaProgram::where('sasaran_opd_id', $request->sasaran_opd_id)
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
        $master_opd_id = $request->get('payload')->opd->id;

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


        $program_renja = RenjaProgram::where('sasaran_opd_id', '=', $sasaran_opd_id)
                ->where('tahun', '=', $request->tahun)
                ->where('murni', '=', $request->murni)
                ->limit(1)
                ->get();     

        if(count($program_renja) > 0)
        {
            $list_prog = [
                'id' => $program_renja[0]['id'],
                'pohon_kinerja_sasaran_id' => $program_renja[0]['pohon_kinerja_sasaran_id'],
                'list_program' => json_decode($program_renja[0]['list_program']),
                'tahun' => $program_renja[0]['tahun'],
                'is_active' => $program_renja[0]['is_active'],
                'murni' => $program_renja[0]['murni'],
            ];
        }
        else
        $list_prog = array();

        $sasaran = [
            'sasaran' =>  $sasaran,
            'list_program' => $list_prog
        ];
       /* $rawActions = SasaranOPD::with(
                        ['program_renja' =>
                        function($query) use ($request) {
                            $master_opd_id = $request->get('payload')->opd->id;
                            $query->where('master_opd_id', $master_opd_id);                                                   
                            $query->where('tahun', $request->tahun);                                                   
                            $query->where('murni', $request->murni);   
                            
                        }
                       ])
                      ->where('id', $sasaran_opd_id)
                      ->get(); */
        


        return response()->json([
            'success' => true,
            'message' => 'Daftar Program Renja  Tahun '.$request->tahun.'  ',
            'data' => $sasaran,
        ]);
    }

    public function generate_pdf(Request $request)
    {        
        $master_opd_id = $request->get('payload')->opd->id;
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
                        ->where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->distinct()
                        ->get(['id', 'sasaran', 'order', 'parent_id', 'tujuan_opd_id']);

        $tahun = !empty($request->tahun) ? $request->tahun : date('Y');
        $murni = !empty($request->murni) ? $request->murni : 'true';

        $sasaran = $sasaran->map(function($item) use ($request, $tahun, $murni)
        {    
            $master_opd_id = $request->get('payload')->opd->id;

            

            $anggaran = RenjaProgram::where('sasaran_opd_id', '=', $item->id)
                            ->where('master_opd_id', '=',  $master_opd_id)
                            ->where('tahun', '=',          $tahun)
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
                $master_opd_id = $request->get('payload')->opd->id;

                $renja = Renja::where('indikator_opd_id', $is->id) 
                ->where('master_opd_id', $master_opd_id) 
                ->where('tahun', $tahun)            
                ->where('murni', $murni)            
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
        
        $periode = ($murni=="true") ? 'Murni' : 'Perubahan';        

            $data = [
                'generated_at' => now()->toDateTimeString(),
                'opd'     => $opd,
                'tahun'   => $tahun,
                'periode' => $periode,
                'sasaran' => $sasaran
            ];
        $pdf = Pdf::loadView('report_template.opd.renja', compact('data'));
        return $pdf->download('Renja_'.$tahun.'_'.str_replace($opd->nama_opd,' ','_').'.pdf');
       
    }
}

<?php

namespace App\Http\Controllers\Api\v1\OPD;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;
use Barryvdh\DomPDF\Facade\Pdf;


class RenstraController extends Controller
{
    public function update($id, Request $request)
    {
        $master_opd_id = $request->get('payload')->opd->id;
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, IKU not Found',
                ], 422);
            }
             // cek existing indikator
            $indikator = IndikatorOpd::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }

         
            // cek validasi jika id berformat uuid atau tidak
            if(!Str::isUuid($request->satuan_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Satuan not Found',
                ], 422);
            }
             // cek existing satuan
            $satuan = MasterSatuan::find($request->satuan_id);
            if (!$satuan) {
                return response()->json([
                    'success' => false,
                    'message' => $satuan.''.$request->satuan,
                ], 404);
            }

            $cek_indikator = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
                ->where('id', '=', $id)
                ->count();

            if ($cek_indikator <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

            $form = $request->validate([                
                "satuan_id"   => "required|string",               
                "target_1" => "required|string",
                "target_2" => "required|string",
                "target_3" => "required|string",
                "target_4" => "required|string",
                "target_5" => "required|string",
                "target_6" => "required|string"
            ]);

            $form['satuan_id'] = $request->satuan_id;            
            $form['target_1'] = $request->target_1;
            $form['target_2'] = $request->target_2;
            $form['target_3'] = $request->target_3;
            $form['target_4'] = $request->target_4;
            $form['target_5'] = $request->target_5;
            $form['target_6'] = $request->target_6;

            $form['updated_by'] = $request->get('payload')->username;

            $indikator->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Renstra updated successfully.',
                'data' => $indikator,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {   
        $master_opd_id = $request->get('payload')->opd->id;
        $sasaran_kdh = BaseController::getSasaranByOPDPengampu($master_opd_id)->pluck('id');  
        
       
         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }

         $visi = BaseController::getCurrentVisi();
         $visi_id = !empty($visi->id) ?  $visi->id : '';

       /* $rawActions = TujuanOPD::with(
          ['sasaran' => 
                function($query) {
                    $query->where('is_active', true);                 
                    $query->with([
                        'indikator_sasaran' =>
                        function($query) {
                            $query->where('is_active', true);                                                   
                            $query->where('is_tujuan', false);                                                   
                        }   
                    ]);                    
                }                                    
        ])
        ->where('master_opd_id', '=', $master_opd_id)
        ->where('pohon_kinerja_visi_id', '=', $visi_id)
        ->get(); */

        $tujuan = tujuanOpd::where('master_opd_id', '=', $master_opd_id)
        ->where('pohon_kinerja_visi_id', '=', $visi_id)
        ->whereIn('pohon_kinerja_sasaran_id', $sasaran_kdh)
        ->where('is_active', true)
        ->orderBy('order', 'ASC')
        ->get();

              

        $tujuan = $tujuan->map(function($item) use ($request)
        {    
            $master_opd_id = $request->get('payload')->opd->id;
            $sasaran = SasaranOpd::where('master_opd_id', '=', $master_opd_id)
                                ->where('tujuan_opd_id', '=', $item->id)
                                ->where('parent_id', '=', 0)
                                ->where('is_active', true)
                                ->orderBy('order', 'ASC')
                                ->get(['id', 'sasaran', 'order', 'parent_id']);

            
            $indikator_opd = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
            ->where('tujuan_opd_id', '=', $item->id)
            ->where('is_tujuan', '=', true)
            ->where('is_active', true)
            ->orderBy('order', 'ASC')
            ->get();

             $indikator_opd = $indikator_opd->map(function($is) use ($request)
                {   
                    $satuan = BaseController::getSatuanByID($is->satuan_id);

                    $visi_id = !empty($visi->id) ?  $visi->id : '';
                    return [
                        "id" => $is->id,
                        "tujuan_opd_id" => $is->id,
                        "sasaran_opd_id" => $is->id,
                        "indikator" => $is->indikator,
                        "order" => $is->order,
                        "defenisi"=> $is->defenisi,
                        "kegunaan"=> $is->kegunaan,
                        "rilis"=> $is->rilis,
                        "sumber_data"=> $is->sumber_data,
                        "satuan_id"=> $is->satuan_id,
                        "satuan"=> $satuan,
                        "baseline"=> $is->baseline,
                        "target_1"=> $is->target_1,
                        "target_2"=> $is->target_2,
                        "target_3"=> $is->target_3,
                        "target_4"=> $is->target_4,
                        "target_5"=> $is->target_5,
                        "target_6"=> $is->target_6,
                        "is_active"=> $is->is_active,
                        "is_indikator_kinerja_utama"=> $is->is_indikator_kinerja_utama,
                        "is_tujuan"=> $is->is_tujuan,
                        "pohon_kinerja_visi_id"=> $is->pohon_kinerja_visi_id,
                        "created_by"=> $is->created_by,
                        "created_at"=> $is->created_at,
                    ];                  
                });


            $sasaran = $sasaran->map(function($item) use ($request)
            {    
                $master_opd_id = $request->get('payload')->opd->id;
                            
                $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                    ->where('master_opd_id', $master_opd_id)  
                                    ->where('is_active', true)
                                    ->orderBy('order', 'ASC')
                                    ->distinct()
                                    ->get();

                $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
                {   
                    $satuan = BaseController::getSatuanByID($is->satuan_id);

                    $visi_id = !empty($visi->id) ?  $visi->id : '';
                    return [
                        "id" => $is->id,
                        "tujuan_opd_id" => $is->id,
                        "sasaran_opd_id" => $is->id,
                        "indikator" => $is->indikator,
                        "order" => $is->order,
                        "defenisi"=> $is->defenisi,
                        "kegunaan"=> $is->kegunaan,
                        "rilis"=> $is->rilis,
                        "sumber_data"=> $is->sumber_data,
                        "satuan_id"=> $is->satuan_id,
                        "satuan"=> $satuan,
                        "baseline"=> $is->baseline,
                        "target_1"=> $is->target_1,
                        "target_2"=> $is->target_2,
                        "target_3"=> $is->target_3,
                        "target_4"=> $is->target_4,
                        "target_5"=> $is->target_5,
                        "target_6"=> $is->target_6,
                        "is_active"=> $is->is_active,
                        "is_indikator_kinerja_utama"=> $is->is_indikator_kinerja_utama,
                        "is_tujuan"=> $is->is_tujuan,
                        "pohon_kinerja_visi_id"=> $is->pohon_kinerja_visi_id,
                        "created_by"=> $is->created_by,
                        "created_at"=> $is->created_at,
                    ];                  
                });

                return [
                    "id" => $item->id,
                    "tujuan_opd_id" => $item->tujuan_opd_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "indikator_sasaran" => $indikator_sasaran,
                ];
            }); 

            return [
                "id" => $item->id,
                "tujuan" => $item->tujuan,
                "order" => $item->order,
                "sasaran" => $sasaran,
                "indikator_tujuan" => $indikator_opd
            ];   
        });
       
        return response()->json([
            'success' => true,
            'message' => 'Pohon KInerja ',
            'data' => $tujuan,
        ]);
    }

      public function generate_pdf(Request $request)
    {   
        $master_opd_id = $request->get('payload')->opd->id;
        $sasaran_kdh = BaseController::getSasaranByOPDPengampu($master_opd_id)->pluck('id');        
        
         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }

         $visi = BaseController::getCurrentVisi();
         $visi_id = !empty($visi->id) ?  $visi->id : '';

        $tujuan = tujuanOpd::where('master_opd_id', '=', $master_opd_id)
        ->where('pohon_kinerja_visi_id', '=', $visi_id)
        ->whereIn('pohon_kinerja_sasaran_id', $sasaran_kdh)
        ->where('is_active', true)
        ->orderBy('order', 'ASC')
        ->get();

        $tujuan = $tujuan->map(function($item) use ($request)
        {    
            $master_opd_id = $request->get('payload')->opd->id;
            $sasaran = SasaranOpd::where('master_opd_id', '=', $master_opd_id)
                                ->where('tujuan_opd_id', '=', $item->id)
                                ->where('parent_id', '=', 0)
                                ->where('is_active', true)
                                ->orderBy('order', 'ASC')
                                ->get(['id', 'sasaran', 'order', 'parent_id']);

            
            $indikator_opd = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
            ->where('tujuan_opd_id', '=', $item->id)
            ->where('is_tujuan', '=', true)
            ->where('is_active', true)
            ->orderBy('order', 'ASC')
            ->get();

             $indikator_opd = $indikator_opd->map(function($is) use ($request)
                {   
                    $satuan = BaseController::getSatuanByID($is->satuan_id);

                    $visi_id = !empty($visi->id) ?  $visi->id : '';
                    return [
                        "id" => $is->id,
                        "tujuan_opd_id" => $is->id,
                        "sasaran_opd_id" => $is->id,
                        "indikator" => $is->indikator,
                        "order" => $is->order,
                        "defenisi"=> $is->defenisi,
                        "kegunaan"=> $is->kegunaan,
                        "rilis"=> $is->rilis,
                        "sumber_data"=> $is->sumber_data,
                        "satuan_id"=> $is->satuan_id,
                        "satuan"=> $satuan,
                        "baseline"=> $is->baseline,
                        "target_1"=> $is->target_1,
                        "target_2"=> $is->target_2,
                        "target_3"=> $is->target_3,
                        "target_4"=> $is->target_4,
                        "target_5"=> $is->target_5,
                        "target_6"=> $is->target_6,
                        "is_active"=> $is->is_active,
                        "is_indikator_kinerja_utama"=> $is->is_indikator_kinerja_utama,
                        "is_tujuan"=> $is->is_tujuan,
                        "pohon_kinerja_visi_id"=> $is->pohon_kinerja_visi_id,
                        "created_by"=> $is->created_by,
                        "created_at"=> $is->created_at,
                    ];                  
                });


            $sasaran = $sasaran->map(function($item) use ($request)
            {    
                $master_opd_id = $request->get('payload')->opd->id;
                            
                $indikator_sasaran = IndikatorOpd::where('sasaran_opd_id', $item->id) 
                                    ->where('master_opd_id', $master_opd_id)  
                                    ->where('is_active', true)
                                    ->orderBy('order', 'ASC')
                                    ->distinct()
                                    ->get();

                $indikator_sasaran = $indikator_sasaran->map(function($is) use ($request)
                {   
                    $satuan = BaseController::getSatuanByID($is->satuan_id);

                    $visi_id = !empty($visi->id) ?  $visi->id : '';
                    return [
                        "id" => $is->id,
                        "tujuan_opd_id" => $is->id,
                        "sasaran_opd_id" => $is->id,
                        "indikator" => $is->indikator,
                        "order" => $is->order,
                        "defenisi"=> $is->defenisi,
                        "kegunaan"=> $is->kegunaan,
                        "rilis"=> $is->rilis,
                        "sumber_data"=> $is->sumber_data,
                        "satuan_id"=> $is->satuan_id,
                        "satuan"=> $satuan,
                        "baseline"=> $is->baseline,
                        "target_1"=> $is->target_1,
                        "target_2"=> $is->target_2,
                        "target_3"=> $is->target_3,
                        "target_4"=> $is->target_4,
                        "target_5"=> $is->target_5,
                        "target_6"=> $is->target_6,
                        "is_active"=> $is->is_active,
                        "is_indikator_kinerja_utama"=> $is->is_indikator_kinerja_utama,
                        "is_tujuan"=> $is->is_tujuan,
                        "pohon_kinerja_visi_id"=> $is->pohon_kinerja_visi_id,
                        "created_by"=> $is->created_by,
                        "created_at"=> $is->created_at,
                    ];                  
                });

                return [
                    "id" => $item->id,
                    "tujuan_opd_id" => $item->tujuan_opd_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "indikator_sasaran" => $indikator_sasaran,
                ];
            }); 

            return [
                "id" => $item->id,
                "tujuan" => $item->tujuan,
                "order" => $item->order,
                "sasaran" => $sasaran,
                "indikator_tujuan" => $indikator_opd
            ];   
        });
       
        /*return response()->json([
            'success' => true,
            'message' => 'Pohon KInerja ',
            'data' => $tujuan,
        ]);*/

        $data = [
            'generated_at' => now()->toDateTimeString(),
            'visi' => $visi,
            'opd' => $opd,
            'tujuan' => $tujuan
        ];
        

        $pdf = Pdf::loadView('report_template.opd.renstra', compact('data'))
                        ->setPaper('Legal', 'landscape');
           return $pdf->download('Renstra_'.$opd->nama_opd.'.pdf');
    }

}

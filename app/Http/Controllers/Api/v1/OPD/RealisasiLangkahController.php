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
use App\Models\Sakip\OPD\Rencana;
use App\Models\Sakip\OPD\RencanaLangkah;

class RealisasiLangkahController extends Controller
{
    public function update($id, Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;

            $detail = RencanaLangkah::find($id);
            if (!$detail) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Langkah Rencana Aksi not found.',
                ], 404);
            }


            $opd = MasterOpd::find($master_opd_id);
            if (!$opd) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Perangkat Daerah not found.',
                ], 404);
            }

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
            
            $update = RencanaLangkah::where('id', '=', $id)              
            ->where('master_opd_id', '=',$master_opd_id)                
            ->update($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully Update Realisasi Langkah Rencana Aksi.',
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
     
        $sasaran_id = $request->get('sasaran_opd_id');
        $indikator_id = $request->get('indikator_opd_id');
        $tahun = $request->get('tahun');
        $master_opd_id = $request->attributes->get('payload')->opd->id;
       
        try {

            $query = RencanaLangkah::query();
            
            $query->where('sasaran_opd_id', $sasaran_id);
            $query->where('indikator_opd_id', $indikator_id);            
            $query->where('tahun', $tahun);            
            $query->orderBy('created_at', 'desc');
            $objData = $query->get();

            // remap
            $objData = $objData->map(function($item) use($request) {
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }
                
               
                $list =  [
                    "id" => $item->id,
                    "langkah" => $item->langkah,
                    "target_tw1" => $item->target_tw1,
                    "target_tw2" => $item->target_tw2,
                    "target_tw3" => $item->target_tw3,
                    "target_tw4" => $item->target_tw4,
                    "realisasi_tw1" => $item->realisasi_tw1,
                    "realisasi_tw2" => $item->realisasi_tw2,
                    "realisasi_tw3" => $item->realisasi_tw3,
                    "realisasi_tw4" => $item->realisasi_tw4,
                    "capaian_tw1" => $item->capaian_tw1,
                    "capaian_tw2" => $item->capaian_tw2,
                    "capaian_tw3" => $item->capaian_tw3,
                    "capaian_tw4" => $item->capaian_tw4,
                    "tahun" => $item->tahun
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Rencana Aksi',
                'data' => $objData
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Rencana Aksi',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}

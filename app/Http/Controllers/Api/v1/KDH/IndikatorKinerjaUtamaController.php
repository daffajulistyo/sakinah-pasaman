<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\KDH\PohonKinerjaTujuan;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\MASTER\MasterOpd;

use Illuminate\Support\Facades\Storage;

class IndikatorKinerjaUtamaController extends Controller
{
    public function update($id, Request $request)
    {
        try {
            
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, IKU not Found',
                ], 422);
            }
             // cek existing indikator
            $indikator = PohonKinerjaIndikator::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }

            $form = $request->validate([                
                "baseline" => "required",
                "formula_perhitungan" => "required|image|mimes:jpeg,png,jpg",
                "sumber_data" => "required|string"
            ]);

            if ($request->hasFile('formula_perhitungan')) {
                    $file = $request->file('formula_perhitungan');
                    // 'uploads' is the directory within your MinIO bucket
                    $path = Storage::disk('s3')->put('uploads', $file); 
            }


            $form['formula_perhitungan'] = $request->formula_perhitungan;
            $form['sumber_data'] = $request->sumber_data;
            $form['defenisi'] = $request->defenisi;
            $form['kegunaan'] = $request->kegunaan;
            $form['rilis'] = $request->rilis;
            $form['updated_by'] = $request->get('payload')->username;

            $indikator->update($form);

            return response()->json([
                'success' => true,
                'message' => 'IKU updated successfully.',
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


    public function create(Request $request)
    {
        try {            

            if(empty($request->indikator_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                    'errors' => "Indikator Wajib Diisi"
                ], 500);
            }

            $current_indikator = BaseController::getIndikatorPemda();

            $update = DB::table('pohon_kinerja_indikator')
                         ->whereIn('id', $current_indikator)
                         ->update(array('is_indikator_kinerja_utama' => false));  
                       
            $indikator_id = $request->indikator_id;
            
            foreach ($indikator_id as $item)
            { 
                $indikator = PohonKinerjaIndikator::find($item);
                if (!$indikator) {
                    return response()->json([
                        'success' => false,
                        'message' => 'indikator not found.',
                    ], 404);
                }

                $update_indikator = DB::table('pohon_kinerja_indikator')
                         ->where('id', $item)
                         ->update(array('is_indikator_kinerja_utama' => true));  
            }           

            return response()->json([
                'success' => true,
                'message' => 'IKU Created successfully.',
                'data' => $indikator_id,
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

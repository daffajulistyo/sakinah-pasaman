<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\Rkpd;
use App\Models\Sakip\KDH\PerjanjianKinerja;
use App\Models\Sakip\KDH\RencanaAksi;
use App\Models\Sakip\KDH\RencanaAksiLangkah;

class RealisasiLangkahController extends Controller
{
    public function update($id, Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Rencana Aksi not Found',
                ], 422);
            }
             // cek existing rkpd
            $detail = RencanaAksiLangkah::find($id);
            if (!$detail) {
                return response()->json([
                    'success' => false,
                    'message' => 'langkah RencanaAksi not found.',
                ], 404);
            }

            $form = $request->validate([   
                "realisasi_tw1" => "required",
                "realisasi_tw2" => "required",
                "realisasi_tw3" => "required",
                "realisasi_tw4" => "required"
            ]);

            $target_tw1 = !empty($detail) ? $detail->target_tw1 : '';
            $target_tw2 = !empty($detail) ? $detail->target_tw2 : '';
            $target_tw3 = !empty($detail) ? $detail->target_tw3 : '';
            $target_tw4 = !empty($detail) ? $detail->target_tw4 : '';

            $capaian_tw1 = !empty($target_tw1) ? ($request->realisasi_tw1/ $target_tw1) * 100 : '0';
            $capaian_tw2 = !empty($target_tw2) ? ($request->realisasi_tw2/ $target_tw2) * 100 : '0';
            $capaian_tw3 = !empty($target_tw3) ? ($request->realisasi_tw3/ $target_tw3) * 100 : '0';
            $capaian_tw4 = !empty($target_tw4) ? ($request->realisasi_tw4/ $target_tw4) * 100 : '0';
            
            $form['updated_by'] = $request->attributes->get('payload')->username;
            $form['capaian_tw1'] = $capaian_tw1;
            $form['capaian_tw2'] = $capaian_tw2;
            $form['capaian_tw3'] = $capaian_tw3;
            $form['capaian_tw4'] = $capaian_tw4;


            $update = RencanaAksiLangkah::where('id', '=', $id)
            ->where('pohon_kinerja_sasaran_id', '=',$request->pohon_kinerja_sasaran_id)
            ->where('pohon_kinerja_indikator_id', '=',$request->pohon_kinerja_indikator_id)                
            ->update($form);

            return response()->json([
                'success' => true,
                'message' => ' Realisasi Langkah RencanaAksi updated successfully.',
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

    public function read($id)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Aksi not Found',
                ], 422);
            }
            // cek data ke database
            $detail = RencanaAksiLangkah::find($id);
            if (!$detail) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Langkah Aksi not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Langkah Aksi found.',
                'data' => $detail,
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

    public function list(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        $tahun = $request->get('tahun');

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $totalPage = 0;
        $totalRecord = 0;
        try {

            $query = RencanaAksiLangkah::query();
            
            $query->where('pohon_kinerja_sasaran_id', $sasaran_id);
            $query->where('pohon_kinerja_indikator_id', $indikator_id);
            $query->where('tahun', $tahun);

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
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
                'message' => 'List of Langkah Rencana Aksi '.$tahun.' ',
                'data' => $objData
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Langkah Rencana Aksi',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}

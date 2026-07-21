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
use App\Models\Sakip\KDH\PerjanjianKinerjaProgram;
use App\Models\Sakip\KDH\RencanaAksi;
use App\Models\Sakip\KDH\RencanaAksiLangkah;

use App\Http\Controllers\Api\v1\MASTER\BaseController;

use Barryvdh\DomPDF\Facade\Pdf;

class RealisasiController extends Controller
{
    public function list(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 1000);
        $search = $request->get('search', '');

        $totalPage = 0;
        $totalRecord = 0;
        try {


            $tujuan = BaseController::getTujuanPemda();
            $query = PohonKinerjaSasaran::query();           
            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            $query->whereIn('pohon_kinerja_tujuan_id', $tujuan);
            if(isset($sasaran_id))
                $query->where('id', $sasaran_id);
            $query->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id');
            $query->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id');
            $query->select('pohon_kinerja_sasaran.*');
            $query->orderBy('pohon_kinerja_misi.order', 'asc');
            $query->orderBy('pohon_kinerja_tujuan.order', 'asc');
            $query->orderBy('pohon_kinerja_sasaran.order', 'asc');
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
                
                $indikator = PohonKinerjaIndikator::where('is_tujuan', '=', false)
                                          ->where('pohon_kinerja_sasaran_id','=', $item->id)
                                          ->get();
                
                $anggaran_murni = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=',$request->tahun)
                ->where('murni', '=',true)
                ->sum('anggaran');

                $anggaran_perubahan = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=',$request->tahun)
                ->where('murni', '=',false)
                ->sum('anggaran');

                $indikator = $indikator->map(function($ik) use($request) 
                {
                    $rencana_aksi = RencanaAksi::where('pohon_kinerja_indikator_id', '=',$ik->id)
                            ->where('tahun', '=', $request->tahun)
                            ->first(['id', 'target_tw1', 'target_tw2', 'target_tw3', 'target_tw4', 'tahun',
                                    'realisasi_tw1', 'realisasi_tw2', 'realisasi_tw3', 'realisasi_tw4',
                                'capaian_tw1', 'capaian_tw2', 'capaian_tw3', 'capaian_tw4', 'hambatan', 'tindak_lanjut'  ]);

                    $langkah = RencanaAksiLangkah::where('pohon_kinerja_indikator_id', '=', $ik->id)
                               ->where('tahun', '=',$request->tahun)
                               ->get();  
                    $langkah = $langkah->map(function($dl) use($request) 
                    {
                        return [
                            "id"                 => $dl->id,
                            "langkah"          => $dl->langkah,
                            "target_tw1"          => $dl->target_tw1,
                            "target_tw2"          => $dl->target_tw2,
                            "target_tw3"          => $dl->target_tw3,
                            "target_tw4"          => $dl->target_tw4,
                            "realisasi_tw1"          => $dl->realisasi_tw1,
                            "realisasi_tw2"          => $dl->realisasi_tw2,
                            "realisasi_tw3"          => $dl->realisasi_tw3,
                            "realisasi_tw4"          => $dl->realisasi_tw4,
                            "capaian_tw1"          => $dl->capaian_tw1,
                            "capaian_tw2"          => $dl->capaian_tw2,
                            "capaian_tw3"          => $dl->capaian_tw3,
                            "capaian_tw4"          => $dl->capaian_tw4
                        ];
                    });                

                    $target_pk = PerjanjianKinerja::where('pohon_kinerja_indikator_id', '=', $ik->id)
                    ->where('tahun', '=',$request->tahun)
                    ->get(['target', 'tahun', 'murni']); 

                    return $listindikator = [
                        "id"                 => $ik->id,
                        "indikator"          => $ik->indikator,
                        "rencana_aksi" => $rencana_aksi,
                        "langkah" => $langkah,
                        "target_perjanjian_kinerja" => $target_pk
                    ];
                });

                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "anggaran_perjanjian_kinerja" => ['murni'=> $anggaran_murni, 'perubahan'=>$anggaran_perubahan],
                    "indikator" =>$indikator
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Rencana Aksi Edit',
                'data' => $objData
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Rencana Aksi Edit',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    public function update($id, Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator not Found',
                ], 422);
            }
            // cek existing sasaran
            $sasaran = PohonKinerjaSasaran::find($request->pohon_kinerja_sasaran_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran not found.',
                ], 404);
            }


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_indikator_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Indikator not Found',
                ], 422);
            }
            // cek existing indikator
            $indikator = PohonKinerjaIndikator::find($request->pohon_kinerja_indikator_id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }

            $detail = RencanaAksi::find($id);
            if (!$detail) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Aksi not found.',
                ], 404);
            }

            
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

                $update = RencanaAksi::where('id', '=', $id)
                ->where('pohon_kinerja_sasaran_id', '=',$request->pohon_kinerja_sasaran_id)
                ->where('pohon_kinerja_indikator_id', '=',$request->pohon_kinerja_indikator_id)                
                ->update($form);
                      
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully Update Realisasi Rencana Aksi.',
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


      public function generate_pdf(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 1000);
        $search = $request->get('search', '');

        $totalPage = 0;
        $totalRecord = 0;
        try {


            $tujuan = BaseController::getTujuanPemda();
            $query = PohonKinerjaSasaran::query();           
            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            $query->whereIn('pohon_kinerja_tujuan_id', $tujuan);
            if(isset($sasaran_id))
                $query->where('id', $sasaran_id);
            $query->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id');
            $query->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id');
            $query->select('pohon_kinerja_sasaran.*');
            $query->orderBy('pohon_kinerja_misi.order', 'asc');
            $query->orderBy('pohon_kinerja_tujuan.order', 'asc');
            $query->orderBy('pohon_kinerja_sasaran.order', 'asc');
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
                
                $indikator = PohonKinerjaIndikator::where('is_tujuan', '=', false)
                                          ->where('pohon_kinerja_sasaran_id','=', $item->id)
                                          ->get();
                
                $anggaran_murni = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=',$request->tahun)
                ->where('murni', '=',true)
                ->sum('anggaran');

                $anggaran_perubahan = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=',$request->tahun)
                ->where('murni', '=',false)
                ->sum('anggaran');

                $indikator = $indikator->map(function($ik) use($request) 
                {
                    $rencana_aksi = RencanaAksi::where('pohon_kinerja_indikator_id', '=',$ik->id)
                            ->where('tahun', '=', $request->tahun)
                            ->first(['id', 'target_tw1', 'target_tw2', 'target_tw3', 'target_tw4', 'tahun',
                                    'realisasi_tw1', 'realisasi_tw2', 'realisasi_tw3', 'realisasi_tw4',
                                'capaian_tw1', 'capaian_tw2', 'capaian_tw3', 'capaian_tw4', 'hambatan', 'tindak_lanjut'  ]);

                    $langkah = RencanaAksiLangkah::where('pohon_kinerja_indikator_id', '=', $ik->id)
                               ->where('tahun', '=',$request->tahun)
                               ->get();  
                    $langkah = $langkah->map(function($dl) use($request) 
                    {
                        return [
                            "id"                 => $dl->id,
                            "langkah"          => $dl->langkah,
                            "target_tw1"          => $dl->target_tw1,
                            "target_tw2"          => $dl->target_tw2,
                            "target_tw3"          => $dl->target_tw3,
                            "target_tw4"          => $dl->target_tw4,
                            "realisasi_tw1"          => $dl->realisasi_tw1,
                            "realisasi_tw2"          => $dl->realisasi_tw2,
                            "realisasi_tw3"          => $dl->realisasi_tw3,
                            "realisasi_tw4"          => $dl->realisasi_tw4,
                            "capaian_tw1"          => $dl->capaian_tw1,
                            "capaian_tw2"          => $dl->capaian_tw2,
                            "capaian_tw3"          => $dl->capaian_tw3,
                            "capaian_tw4"          => $dl->capaian_tw4
                        ];
                    });                

                    $target_pk = PerjanjianKinerja::where('pohon_kinerja_indikator_id', '=', $ik->id)
                    ->where('tahun', '=',$request->tahun)
                    ->get(['target', 'tahun', 'murni']); 

                    return $listindikator = [
                        "id"                 => $ik->id,
                        "indikator"          => $ik->indikator,
                        "rencana_aksi" => $rencana_aksi,
                        "langkah" => $langkah,
                        "target_perjanjian_kinerja" => $target_pk
                    ];
                });

                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "anggaran_perjanjian_kinerja" => ['murni'=> $anggaran_murni, 'perubahan'=>$anggaran_perubahan],
                    "indikator" =>$indikator
                ];
                
                return $list;
            });

             
            $data = [
                'generated_at' => now()->toDateTimeString(),
                'tahun' => $request->tahun,
                'sasaran' => $objData
            ];
            $pdf = Pdf::loadView('report_template.kdh.realisasi', compact('data'))->setPaper('Legal', 'portrait');;
            return $pdf->download('Realisasi'.$request->tahun.'.pdf');
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Rencana Aksi Edit',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}

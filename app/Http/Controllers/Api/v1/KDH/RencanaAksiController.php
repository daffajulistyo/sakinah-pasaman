<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\Rkpd;
use App\Models\Sakip\KDH\PerjanjianKinerja;
use App\Models\Sakip\KDH\PerjanjianKinerjaProgram;
use App\Models\Sakip\KDH\RencanaAksi;
use App\Models\Sakip\KDH\RencanaAksiLangkah;

use Barryvdh\DomPDF\Facade\Pdf;

class RencanaAksiController extends Controller
{
    public function create(Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, PerjanjianKinerja not Found',
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

            // cek existing target
            $target = RencanaAksi::where('tahun', '=', $request->tahun)
                      ->where('pohon_kinerja_sasaran_id', '=',$request->pohon_kinerja_sasaran_id)
                      ->where('pohon_kinerja_indikator_id', '=',$request->pohon_kinerja_indikator_id)                      
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
                $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
                $form['pohon_kinerja_indikator_id'] = $request->pohon_kinerja_indikator_id;
                $form['target_tw1'] = $request->target_tw1;
                $form['target_tw2'] = $request->target_tw2;
                $form['target_tw3'] = $request->target_tw3;
                $form['target_tw4'] = $request->target_tw4;
    
                // create uuid and assign author
                $form['id'] = Str::uuid();
                $form['created_by'] = $request->get('payload')->username;
                
                // insert into table db
                $data = RencanaAksi::create($form);
            }
            else
            {
                $form['target_tw1'] = $request->target_tw1;
                $form['target_tw2'] = $request->target_tw2;
                $form['target_tw3'] = $request->target_tw3;
                $form['target_tw4'] = $request->target_tw4;

                $update = RencanaAksi::where('tahun', '=', $request->tahun)
                ->where('pohon_kinerja_sasaran_id', '=',$request->pohon_kinerja_sasaran_id)
                ->where('pohon_kinerja_indikator_id', '=',$request->pohon_kinerja_indikator_id)                
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
            $detail = RencanaAksi::find($id);
            if (!$detail) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Aksi not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Aksi found.',
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

    public function delete($id)
    {
        try {
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, RencanaAksi not Found',
                ], 422);
            }
            $cek = RencanaAksi::find($id);
            if (!$cek) {
                return response()->json([
                    'success' => false,
                    'message' => 'RencanaAksi not found.',
                ], 404);
            }
            $cek->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'RencanaAksi deleted successfully.',
                'data' => $cek,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    /*--------------------List Sasaran dan PK------------------------------*/
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
            $query->whereIn('pohon_kinerja_tujuan_id', $tujuan);

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            if(isset($sasaran_id))
                $query->where('id', $sasaran_id);
            $query->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id');
            $query->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id');
            $query->select('pohon_kinerja_sasaran.*');
            $query->orderBy('pohon_kinerja_misi.order', 'asc');
            $query->orderBy('pohon_kinerja_tujuan.order', 'asc');
            $query->orderBy('pohon_kinerja_sasaran.order', 'asc');
            $objData = $query->paginate($perPage);
            $totalPage = $objData->lastPage();
            $totalRecord = $objData->total();

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
                            ->get(['id', 'target_tw1', 'target_tw2', 'target_tw3', 'target_tw4', 'tahun'])
                            ->first();

                    $langkah = RencanaAksiLangkah::where('pohon_kinerja_indikator_id', '=', $ik->id)
                               ->where('tahun', '=',$request->tahun)
                               ->get();  
                    $langkah = $langkah->map(function($dl) use($request) 
                    {
                        return [
                            "id"                 => $dl->id,
                            "langkah"          => $dl->langkah
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
                'message' => 'List of Rencana Aksi',
                'data' => $objData,
                'pagination' => [
                    'page' => $currentPage,
                    'per_page' => $perPage,
                    'total_records' => $totalRecord,
                    'total_page' => $totalPage,
                    'search' => $search
                ]
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Rencana Aksi',
                'data' => [],
                'pagination' => [
                    'page' => $currentPage,
                    'per_page' => $perPage,
                    'total_records' => $totalRecord,
                    'total_page' => $totalPage,
                    'search' => $search
                ],
                'errors' => $th->getMessage()
            ], 500);
        }
    }
    /*--------------------List Sasaran dan PK------------------------------*/

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
            $query->whereIn('pohon_kinerja_tujuan_id', $tujuan);

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            if(isset($sasaran_id))
                $query->where('id', $sasaran_id);
            $query->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id');
            $query->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id');
            $query->select('pohon_kinerja_sasaran.*');
            $query->orderBy('pohon_kinerja_misi.order', 'asc');
            $query->orderBy('pohon_kinerja_tujuan.order', 'asc');
            $query->orderBy('pohon_kinerja_sasaran.order', 'asc');
            $objData = $query->paginate($perPage);
            $totalPage = $objData->lastPage();
            $totalRecord = $objData->total();

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
                            ->get(['id', 'target_tw1', 'target_tw2', 'target_tw3', 'target_tw4', 'tahun'])
                            ->first();

                    $langkah = RencanaAksiLangkah::where('pohon_kinerja_indikator_id', '=', $ik->id)
                               ->where('tahun', '=',$request->tahun)
                               ->get();  
                    $langkah = $langkah->map(function($dl) use($request) 
                    {
                        return [
                            "id"                 => $dl->id,
                            "langkah"          => $dl->langkah
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

            $periode = ($request->murni=="true") ? 'Murni' : 'Perubahan';
            $data = [
                'generated_at' => now()->toDateTimeString(),
                'tahun' => $request->tahun,
                'periode' => $periode,
                'sasaran' => $objData
            ];
            $pdf = Pdf::loadView('report_template.kdh.aksi', compact('data'))->setPaper('Legal', 'portrait');;
            return $pdf->download('Rencana Aksi'.$request->tahun.'.pdf');

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Rencana Aksi',
                'data' => [],
                'pagination' => [
                    'page' => $currentPage,
                    'per_page' => $perPage,
                    'total_records' => $totalRecord,
                    'total_page' => $totalPage,
                    'search' => $search
                ],
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}

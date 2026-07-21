<?php

namespace App\Http\Controllers\Api\v1\KDH;

use App\Models\Sakip\KDH\PerjanjianKinerjaProgram;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\Rkpd;
use App\Models\Sakip\KDH\PerjanjianKinerja;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use Barryvdh\DomPDF\Facade\Pdf;

class PerjanjianKinerjaController extends Controller
{
    public function create(Request $request)
    {
        try {

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
            // cek existing sasaran
            $sasaran = PohonKinerjaSasaran::find($request->pohon_kinerja_sasaran_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran not found.',
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
            $target = PerjanjianKinerja::where('tahun', '=', $request->tahun)
                      ->where('pohon_kinerja_sasaran_id', '=',$request->pohon_kinerja_sasaran_id)
                      ->where('pohon_kinerja_indikator_id', '=',$request->pohon_kinerja_indikator_id)
                      ->where('murni', '=', $request->murni)
                      ->get();
                         
            //validasi payload
            $form = $request->validate([
                "tahun" => "required|integer",
                "target" => "required",
                "murni" => "required|boolean"
            ]);

            if (count($target) <= 0) {
                    $form['pohon_kinerja_indikator_id'] = $request->pohon_kinerja_indikator_id;
                    $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;

                    // create uuid and assign author
                    $form['id'] = Str::uuid();
                    $form['created_by'] = $request->get('payload')->username;
                    
                    // insert into table db
                    $data = PerjanjianKinerja::create($form);
            }
            else{
                $form['target'] = $request->target;
                $update = PerjanjianKinerja::where('tahun', '=', $request->tahun)
                ->where('pohon_kinerja_sasaran_id', '=',$request->pohon_kinerja_sasaran_id)
                ->where('pohon_kinerja_indikator_id', '=',$request->pohon_kinerja_indikator_id)
                ->where('murni', '=', $request->murni)
                ->update($form);
            }
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Target PerjanjianKinerja.',
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
                    'message' => 'Invalid Id, Perjanjian Kinerja not Found',
                ], 422);
            }
            // cek data ke database
            $detail = PerjanjianKinerja::find($id);
            if (!$detail) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Perjanjian Kinerja not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Perjanjian Kinerja found.',
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


    public function update($id, Request $request)
    {
        try {

             // cek validasi jika id berformar uuid atau tidak
             if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
            // cek existing sasaran
            $sasaran = PohonKinerjaSasaran::find($request->pohon_kinerja_sasaran_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran not found.',
                ], 404);
            }


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, PerjanjianKinerja not Found',
                ], 422);
            }
             // cek existing rkpd
            $PerjanjianKinerja = PerjanjianKinerja::find($id);
            if (!$PerjanjianKinerja) {
                return response()->json([
                    'success' => false,
                    'message' => 'PerjanjianKinerja not found.',
                ], 404);
            }

             // cek existing target
             $target = PerjanjianKinerja::where('tahun', $request->tahun)
                            ->where('id', '!=', $id)
                            ->where('pohon_kinerja_sasaran_id', $request->pohon_kinerja_sasaran_id)             
                            ->where('pohon_kinerja_indikator_id', $request->pohon_kinerja_indikator_id)             
                            ->where('murni', $request->murni)
                            ->get();
                                
                if (count($target) > 0) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'indikator '.$request->tahun.' sudah ada.',
                    ], 404);
                }


            $form = $request->validate([
                "tahun" => "required|integer",
                "target" => "required",
                "murni" => "required|boolean"
            ]);
            $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
            $form['pohon_kinerja_indikator_id'] = $request->pohon_kinerja_indikator_id;
            $form['updated_by'] = $request->get('payload')->username;

            $PerjanjianKinerja->update($form);

            return response()->json([
                'success' => true,
                'message' => 'PerjanjianKinerja updated successfully.',
                'data' => $PerjanjianKinerja,
            ]);
        } catch (\Throwable $th) {
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
                    'message' => 'Invalid Id, PerjanjianKinerja not Found',
                ], 422);
            }
            $cek = PerjanjianKInerja::find($id);
            if (!$cek) {
                return response()->json([
                    'success' => false,
                    'message' => 'PerjanjianKinerja not found.',
                ], 404);
            }
            $cek->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'PerjanjianKInerja deleted successfully.',
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


    

    /*--------------------Update Target Per Tri Wulan------------------------------*/
    public function updateTargetRencanaAksi($id, Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Perjanjian Kinerja not Found',
                ], 422);
            }
             // cek existing pk
            $pk = PerjanjianKinerja::find($id);
            if (!$pk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perjanjian Kinerja not found.',
                ], 404);
            }

            $form = $request->validate([                
                "target_tw1" => "required",
                "target_tw2" => "required",
                "target_tw3" => "required",
                "target_tw4" => "required"
            ]);
            
            $form['updated_by'] = $request->get('payload')->username;

            $pk->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Target updated successfully.',
                'data' => $pk,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }
    /*--------------------Update Target Per Tri Wulan------------------------------*/

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
                
                $indikator = PohonKinerjaIndikator::where('is_tujuan', false)
                                          ->where('pohon_kinerja_sasaran_id', $item->id)
                                          ->get();
                
                $program = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=', $request->get('tahun'))
                ->where('murni', '=', $request->get('murni'))
                ->get();

                $program = $program->map(function($dp) use($request) 
                {
                    return $list_kegiatan = [
                        "id"            => $dp->id,
                        "pohon_kinerja_sasaran_id"    => $dp->pohon_kinerja_sasaran_id,
                        "list_kegiatan"    => json_decode($dp->list_kegiatan),
                        "tahun"    => $dp->tahun,
                        "anggaran"    => $dp->anggaran,                        
                        "is_active"     => $dp->is_active,
                        "murni"     => $dp->murni
                    ];
                });

                $anggaran = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=', $request->get('tahun'))
                ->where('murni', '=', $request->get('murni'))
                ->sum('anggaran');
             
                $indikator = $indikator->map(function($ik) use($request) 
                {
                    $perjanjian_kinerja = PerjanjianKinerja::where('pohon_kinerja_indikator_id', $ik->id)
                            ->where('tahun', '=',$request->get('tahun'))
                            ->where('murni', '=', $request->get('murni'))
                            ->get(['id', 'target', 'murni', 'tahun'])
                            ->first();

                    return $listindikator = [
                        "id"                 => $ik->id,
                        "indikator"          => $ik->indikator,
                        "perjanjian_kinerja" => $perjanjian_kinerja
                    ];
                });

                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "list_program" => $program,
                    "order" => $item->order,
                    "indikator" =>$indikator,
                    "anggaran" =>$anggaran
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Perjanjian Kinerjaah',
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
                'message' => 'List of Perjanjian Kinerjaah',
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
                
                $indikator = PohonKinerjaIndikator::where('is_tujuan', false)
                                          ->where('pohon_kinerja_sasaran_id', $item->id)
                                          ->get();
                
                $program = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=', $request->get('tahun'))
                ->where('murni', '=', $request->get('murni'))
                ->get();

                $program = $program->map(function($dp) use($request) 
                {
                    return $list_kegiatan = [
                        "id"            => $dp->id,
                        "pohon_kinerja_sasaran_id"    => $dp->pohon_kinerja_sasaran_id,
                        "list_kegiatan"    => json_decode($dp->list_kegiatan),
                        "tahun"    => $dp->tahun,
                        "anggaran"    => $dp->anggaran,                        
                        "is_active"     => $dp->is_active,
                        "murni"     => $dp->murni
                    ];
                });

                $anggaran = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=', $request->get('tahun'))
                ->where('murni', '=', $request->get('murni'))
                ->sum('anggaran');
             
                $indikator = $indikator->map(function($ik) use($request) 
                {
                    $perjanjian_kinerja = PerjanjianKinerja::where('pohon_kinerja_indikator_id', $ik->id)
                            ->where('tahun', '=',$request->get('tahun'))
                            ->where('murni', '=', $request->get('murni'))
                            ->get(['id', 'target', 'murni', 'tahun'])
                            ->first();

                    return $listindikator = [
                        "id"                 => $ik->id,
                        "indikator"          => $ik->indikator,
                        "perjanjian_kinerja" => $perjanjian_kinerja
                    ];
                });

                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "list_program" => $program,
                    "order" => $item->order,
                    "indikator" =>$indikator,
                    "anggaran" =>$anggaran
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
            $pdf = Pdf::loadView('report_template.kdh.pk', compact('data'))->setPaper('Legal', 'portrait');;
            return $pdf->download('PerjanjianKinerja_'.$request->tahun.'.pdf');

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Perjanjian Kinerjaah',
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

<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\Cascading;

use App\Models\Sakip\KDH\Rkpd;
use App\Models\Sakip\KDH\RkpdKegiatan;

use App\Http\Controllers\Api\v1\MASTER\BaseController;
use Barryvdh\DomPDF\Facade\Pdf;

class RkpdController extends Controller
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
            // cek existing indikator
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
            $target = Rkpd::where('tahun', '=', $request->tahun)
                      ->where('pohon_kinerja_sasaran_id', '=', $request->pohon_kinerja_sasaran_id)
                      ->where('pohon_kinerja_indikator_id', '=', $request->pohon_kinerja_indikator_id)
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
               

                $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
                $form['pohon_kinerja_indikator_id'] = $request->pohon_kinerja_indikator_id;

                // create uuid and assign author
                $form['id'] = Str::uuid();
                $form['created_by'] = $request->attributes->get('payload')->username;
                
                // insert into table db
                $data = Rkpd::create($form);
            }
            else
            {
                $form['target'] = $request->target;
                $update = Rkpd::where('tahun', '=', $request->tahun)
                      ->where('pohon_kinerja_sasaran_id', '=', $request->pohon_kinerja_sasaran_id)
                      ->where('pohon_kinerja_indikator_id', '=', $request->pohon_kinerja_indikator_id)
                      ->where('murni', '=', $request->murni)
                      ->update($form);
            }
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Target RKPD.',
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
                    'message' => 'Invalid Id, RKPD not Found',
                ], 422);
            }
            // cek data ke database
            $rkpd = Rkpd::find($id);
            if (!$rkpd) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'RKPD not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'rkpd found.',
                'data' => $rkpd,
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
            // cek existing indikator
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
                    'message' => 'Invalid Id, RKPD not Found',
                ], 422);
            }
             // cek existing rkpd
            $rkpd = Rkpd::find($id);
            if (!$rkpd) {
                return response()->json([
                    'success' => false,
                    'message' => 'rkpd not found.',
                ], 404);
            }

             // cek existing target
             $target = Rkpd::where('tahun', $request->tahun)
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
            $form['updated_by'] = $request->attributes->get('payload')->username;

            $rkpd->update($form);

            return response()->json([
                'success' => true,
                'message' => 'rkpd updated successfully.',
                'data' => $rkpd,
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
                    'message' => 'Invalid Id, RKPD not Found',
                ], 422);
            }
            $rkpd = Rkpd::find($id);
            if (!$rkpd) {
                return response()->json([
                    'success' => false,
                    'message' => 'rkpd not found.',
                ], 404);
            }
            $rkpd->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'rkpd deleted successfully.',
                'data' => $rkpd,
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


    public function list(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        $murni = $request->get('murni');
        $tahun = $request->get('tahun');

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
                                          ->orderBy('order', 'asc')
                                          ->get();

                $program = RkpdKegiatan::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=', $request->tahun)
                ->where('murni', '=', $request->murni)
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


                $indikator = $indikator->map(function($ik) use($request) 
                {   
                    $murni = $request->get('murni');
                    
                    /*if($murni)
                    $tahun = date('Y') + 1;
                    else
                    $tahun = date('Y');*/
                    $tahun = $request->tahun;
                    $indikator_id = $ik->id;

                    $rkpd = Rkpd::where('pohon_kinerja_indikator_id', $ik->id)
                            ->where('tahun', $tahun)
                            ->where('murni', $murni)
                            ->get();

                    return $listindikator = [
                        "id"        => $ik->id,
                        "indikator" => $ik->indikator,
                        "rkpd"      => $rkpd,
                        "target_rpjmd" => $this->getTargetRpjmd($tahun, $indikator_id),
                    ];
                });


                $anggaran = RkpdKegiatan::where('pohon_kinerja_sasaran_id', $item->id)
                ->where('tahun', $request->tahun)
                ->where('murni', $request->murni)
                ->limit(1)
                ->sum('anggaran');

                //$anggaran = $anggaran->pluck('anggaran');
                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "indikator" =>$indikator,
                    "program" =>$program,
                    "anggaran" => $anggaran
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of RKPD',
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
                'message' => 'List of RKPD Sasaran',
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

    public function getTargetRpjmd($tahun, $indikator_id)
    {
        // Cek Target RPJMD
         $visi = BaseController::getCurrentVisi();             
         $tahun_awal = !empty($visi) ? $visi->period_starts : 0;             
         $tahun_akhir = !empty($visi) ? $visi->period_ends : 0;  

         $periode = array();
         for ($i = $tahun_awal; $i <= $tahun_akhir; $i++) {
            $periode[] = $i;
         }
        
         //if(in_array($tahun))
         return 199;
    }

    public function generate_pdf(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        $murni = $request->get('murni');
        $tahun = $request->get('tahun');

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
                                          ->orderBy('order', 'asc')
                                          ->get();

                $program = RkpdKegiatan::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=', $request->tahun)
                ->where('murni', '=', $request->murni)
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


                $indikator = $indikator->map(function($ik) use($request) 
                {   
                    $murni = $request->get('murni');
                    
                    /*if($murni)
                    $tahun = date('Y') + 1;
                    else
                    $tahun = date('Y');*/
                    $tahun = $request->tahun;
                    $indikator_id = $ik->id;

                    $rkpd = Rkpd::where('pohon_kinerja_indikator_id', $ik->id)
                            ->where('tahun', $tahun)
                            ->where('murni', $murni)
                            ->get();

                    return $listindikator = [
                        "id"        => $ik->id,
                        "indikator" => $ik->indikator,
                        "rkpd"      => $rkpd,
                        "target_rpjmd" => $this->getTargetRpjmd($tahun, $indikator_id),
                    ];
                });


                $anggaran = RkpdKegiatan::where('pohon_kinerja_sasaran_id', $item->id)
                ->where('tahun', $request->tahun)
                ->where('murni', $request->murni)
                ->limit(1)
                ->sum('anggaran');

                //$anggaran = $anggaran->pluck('anggaran');
                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "indikator" =>$indikator,
                    "program" =>$program,
                    "anggaran" => $anggaran
                ];
                
                return $list;
            });

            $periode = ($request->murni=="true") ? 'Murni' : 'Perubahan';        

            $data = [
                'generated_at' => now()->toDateTimeString(),
                'tahun'   => $request->tahun,
                'periode' => $periode,
                'sasaran' => $objData
            ];
            $pdf = Pdf::loadView('report_template.kdh.rkpd', compact('data'));
            return $pdf->download('RKPD'.$request->tahun.'.pdf');

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of RKPD Sasaran',
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

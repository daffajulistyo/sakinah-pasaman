<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\Cascading;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\OpdPendukungIndikator;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\MASTER\MasterOpd;


class CascadingController extends Controller
{
    public function create(Request $request)
    {
        try {
           
            /*------------ cek validasi Sasaran-------------------------------------*/
           
            //test

            $test = collect($request);

            $insert =array();
            
            foreach (json_decode($test) as $req)
            {   
                $pohon_kinerja_sasaran_id = $req->pohon_kinerja_sasaran_id;
                $sasaran = PohonKinerjaSasaran::where('id', $req->pohon_kinerja_sasaran_id)->get();


                $cascading =  Cascading::where('pohon_kinerja_sasaran_id', '=', $req->pohon_kinerja_sasaran_id)
                        ->where('tahun', '=',  $req->tahun, 'and')
                        ->where('id_skpd', '=',  $req->id_skpd)
                        ->delete();
               
                
                        $form['id'] = Str::uuid();
                        $form['pohon_kinerja_sasaran_id'] = $req->pohon_kinerja_sasaran_id;
                        $form['tahun'] = $req->tahun;
                        $form['id_program'] = $req->id_program;
                        $form['kode_program'] = $req->kode_program;
                        $form['nama_program'] = $req->nama_program;
                        $form['id_skpd'] = $req->id_skpd;
                        $form['order'] = 1;
                        $form['is_active'] = true;
    
                        $form['created_by'] = $request->get('payload')->username;
                                            
                    $insert[] = $form;   
            }

            // insert into table db
            $data = Cascading::insert($insert);   

            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created cascading pemerintah daerah.',
                'data' => $insert,
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
        //
        $searchColumn = collect(['nama_program']);
        $pohon_kinerja_sasaran_id = $request->get('pohon_kinerja_sasaran_id');
        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $totalPage = 0;
        $totalRecord = 0;
        try {

            $query = Cascading::query();
            $query->where('pohon_kinerja_sasaran_id', "=", $pohon_kinerja_sasaran_id);

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
            $query->orderBy('created_at', 'desc');
            $objData = $query->paginate($perPage);
            $totalPage = $objData->lastPage();
            $totalRecord = $objData->total();

            // remap
            $objData = $objData->map(function($item){
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }
                return [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "order" => $item->order,
                    "id_program" => $item->id_program,
                    "is_active" => $item->is_active,
                    
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List Program Cascading',
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
                'message' => 'List Program Cascading',
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

    public function delete($id)
    {
        try {
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Program not Found',
                ], 422);
            }
            $program = Cascading::find($id);
            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found.',
                ], 404);
            }
            $program->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Program deleted successfully.',
                'data' => $program,
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


    public function showallsasaran(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);        
        $search = $request->get('search', '');
        
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
            $query->orderBy('created_at', 'desc');
            $objData = $query->get();

            $id_sasaran = $objData->pluck('id');

            

           /* $indikator = PohonKinerjaIndikator::with(['opd_pendukung' => function ($query) {
                $query->select('opd_pendukung_indikator.id as id', 'nama_opd', 'website', 'simpeg_opd_id', 'ikd_opd_id');
             }])->whereIn('pohon_kinerja_sasaran_id', $id_sasaran)->get();
            */
            

            // remap
            $objData = $objData->map(function($item){
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }


               /* $indikator = PohonKinerjaIndikator::where('pohon_kinerja_sasaran_id', '=', $item->pohon_kinerja_sasaran_id)->get();
                    
                $id_indikator = $indikator->pluck('id'); */
                $indikator = PohonKinerjaIndikator::with(['opd_pendukung' => function ($query) {
                    $query->select('opd_pendukung_indikator.id as id', 'nama_opd', 'website', 'simpeg_opd_id', 'ikd_opd_id');
                 }])->where('pohon_kinerja_sasaran_id', $item->id)->get();

                 $id_indikator = $indikator->pluck('id');
                
                 $opd = OpdPendukungIndikator::join('master_opd', 'master_opd.id', '=', 'opd_pendukung_indikator.master_opd_id')
                 ->whereIn('pohon_kinerja_indikator_id', $id_indikator)
                 //->groupBy('opd_pendukung_indikator.master_opd_id, master_opd.kode_opd')
                 ->distinct()
                ->get(['opd_pendukung_indikator.master_opd_id', 'master_opd.kode_opd', 'master_opd.nama_opd' , 'master_opd.simpeg_opd_id', 'master_opd.ikd_opd_id', 'master_opd.simonev_opd_id', 'master_opd.kode_sub_opd',]);

                $program = Cascading::where('pohon_kinerja_sasaran_id', $item->id)->get();
                return [
                    "id" => $item->id,
                    "pohon_kinerja_tujuan_id" => $item->pohon_kinerja_tujuan_id,
                    "order" => $item->order,
                    "sasaran" => $item->sasaran,
                    "opd_pendukung" => $opd,
                    "program" => $program,
                    "is_active" => $item->is_active,
                    "created_at" => $created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Sasaran dan OPD Pendukung',
                'data' => $objData
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Sasaran dan OPD Pendukung',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }
 
}

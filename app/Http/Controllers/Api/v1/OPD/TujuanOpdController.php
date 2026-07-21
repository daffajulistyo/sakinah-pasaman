<?php

namespace App\Http\Controllers\Api\v1\OPD;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rules\RequiredIf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\OpdPendukungIndikator;
use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\MASTER\MasterOpd;

class TujuanOpdController extends Controller
{
    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_sasaran_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran Pemda not Found',
                ], 422);
            }

            // cek existing tujuan pemda
            $sasaran = PohonKinerjaSasaran::find($request->pohon_kinerja_sasaran_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Pemda not found.',
                ], 404);
            }

           

            // cek existing opd
            $opd = MasterOpd::find($master_opd_id);
            if (!$opd) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Perangkat Daerah not found.',
                ], 404);
            }


            $opd_pendukung = OpdPendukungIndikator::where('master_opd_id', '=', $master_opd_id)
                            ->where('pohon_kinerja_sasaran_id', '=', $request->pohon_kinerja_sasaran_id)
                            ->count();
            
            if ($opd_pendukung <= 0 ) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => ' Sasaran Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }
           
            $visi = BaseController::getVisiBySasaranID($request->pohon_kinerja_sasaran_id);
            $visi_id =  $visi[0]->visi_id;
            if (!$visi ) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Visi Not Found',
                ], 404);
            }

            //validasi payload
            $form = $request->validate([
                "order"          => "required|integer",                
                "is_direct"      => "required|boolean",
                "tujuan" => "required|string",
                "is_active"      => "required|boolean",
            ]);
            $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;            
            $form['master_opd_id'] = $master_opd_id;
            $form['pohon_kinerja_visi_id'] = $visi_id;

            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->get('payload')->username;
            
            // insert into table db
            $data = TujuanOPD::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created tujuan perangkat daerah.',
                'data' => $data,
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

    public function update($id, Request $request)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan Opd not Found',
                ], 422);
            }
             // cek existing tujuan
            $tujuan = TujuanOpd::find($id);
            if (!$tujuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tujuan Opd not found.',
                ], 404);
            }

           // cek existing tujuan pemda
           $sasaran_pemda = PohonKinerjaSasaran::find($request->pohon_kinerja_sasaran_id);
           if (!$sasaran_pemda) {
               // jika data tidak ditamukan di dalam database
               return response()->json([
                   'success' => false,
                   'message' => 'Sasaran Pemda not found.',
               ], 404);
           }

           // cek existing opd
           $opd = MasterOpd::find($master_opd_id);
           if (!$opd) {
               // jika data tidak ditamukan di dalam database
               return response()->json([
                   'success' => false,
                   'message' => 'Perangkat Daerah not found.',
               ], 404);
           }


           $opd_pendukung = OpdPendukungIndikator::where('master_opd_id', '=', $master_opd_id)
            ->where('pohon_kinerja_sasaran_id', '=', $request->pohon_kinerja_sasaran_id)
            ->count();

            if ($opd_pendukung <= 0 ) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => ' Sasaran Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

            $visi = BaseController::getVisiBySasaranID($request->pohon_kinerja_sasaran_id);
                $visi_id =  $visi[0]->visi_id;
                if (!$visi ) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => 'Visi Not Found',
                ], 404);
            }

          
           //validasi payload
           $form = $request->validate([
               "order"          => "required|integer",                               
               "is_direct"      => "required|boolean",
               //"tujuan"   => new RequiredIf($request->is_direct == false),                
               "tujuan" => "required|string",
               "is_active"      => "required|boolean",
           ]);

           $form['pohon_kinerja_sasaran_id'] = $request->pohon_kinerja_sasaran_id;
           $form['master_opd_id'] = $master_opd_id;
           $form['pohon_kinerja_visi_id'] = $visi_id;
           $form['updated_by'] = $request->get('payload')->username;

            $tujuan->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Tujuan OPD updated successfully.',
                'data' => $tujuan,
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
                    'message' => 'Invalid Id, Tujuan not Found',
                ], 422);
            }

            // cek data ke database
            $tujuan = TujuanOpd::find($id);

            if (!$tujuan) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Tujuan not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Tujuan found.',
                'data' => $tujuan,
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

    public function delete(Request $request, $id)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;

            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan not Found',
                ], 422);
            }
            $tujuan = TujuanOpd::find($id);
            if (!$tujuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tujuan OPD not found.',
                ], 404);
            }


            $opd_pendukung = TujuanOpd::where('master_opd_id', '=', $master_opd_id)
            ->where('id', '=', $id)
            ->count();

            if ($opd_pendukung <= 0 ) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                'message' => ' Sasaran Bukan Di Ampu Oleh OPD Terkait',
                ], 409);
            }

            /*-----------------------Cek Jika tujuan digunakan di data lain -------------------*/
            $sasaran_opd = SasaranOpd::where('master_opd_id', '=', $master_opd_id)
            ->where('tujuan_opd_id', '=', $id)
            ->count();

            if ($sasaran_opd > 0 ) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                'success' => false,
                            'message' => 'Something went wrong!',
                            'errors' => 'Tujuan Tidak Bisa Dihapus karena sedang digunakan dalam pohon kinerja (sasaran)'
                ], 409);
            }

            $cek_indikator = DB::table('indikator_opd')
                                ->where('tujuan_opd_id', $id)
                                ->where('deleted_at', '=', NULL)
                                ->count();
            if ($cek_indikator > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Something went wrong!',
                            'errors' => 'Tujuan Tidak Bisa Dihapus karena sedang digunakan dalam pohon kinerja (indikator)'
                        ], 409);
            }
                   
            $rencana = DB::table('rencana_opd_langkah a')
                            ->where('a.tujuan_opd_id', $id)
                            ->where('a.deleted_at', '=', NULL)
                            ->join('sasaran_opd b', 'b.id', '=', 'a.sasaran_opd_id')
                            ->count();

            if ($rencana > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Something went wrong!',
                            'errors' => 'Tujuan Tidak Bisa Dihapus karena sedang digunakan dalam data rencana aksi'
                        ], 409);
            }
            /*-----------------------Cek Jika tujuan digunakan di data lain -------------------*/

            $tujuan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Tujuan OPD deleted successfully.',
                'data' => $tujuan,
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
        $searchColumn = collect(['sasaran', 'tujuan']);
        
        $master_opd_id = $request->get('payload')->opd->id;

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $totalPage = 0;
        $totalRecord = 0;
        try {

            $sasaran_kdh = BaseController::getSasaranByOPDPengampu($master_opd_id)->pluck('id');
            
            $query = TujuanOpd::query();                                       

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
            
            $query->select('tujuan_opd.id','tujuan_opd.pohon_kinerja_sasaran_id','pohon_kinerja_sasaran.sasaran','tujuan_opd.master_opd_id','tujuan_opd.order','tujuan_opd.tujuan','tujuan_opd.is_direct','tujuan_opd.is_active','tujuan_opd.created_at');
            $query->where('master_opd_id', "=", $master_opd_id);
            $query->whereIn('pohon_kinerja_sasaran_id',  $sasaran_kdh);  
            $query->join('pohon_kinerja_sasaran','pohon_kinerja_sasaran.id', '=', 'tujuan_opd.pohon_kinerja_sasaran_id');         
            $query->orderBy('tujuan_opd.order', 'asc');

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
                    "sasaran" => $item->sasaran,
                    "master_opd_id" => $item->master_opd_id,
                    "order" => $item->order,                    
                    "tujuan" => $item->tujuan,
                    "is_direct" => $item->is_direct,
                    "is_active" => $item->is_active,
                    "created_at" => $created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Pohon Kinerja Tujuan',
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
                'message' => 'List of Pohon Kinerja Tujuan OPD',
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


    public function getSasaranKDH(Request $request)
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


        $visi = BaseController::getSasaranByOPDPengampu($master_opd_id);
        return response()->json([
            'success' => true,
            'message' => 'List Sasaran KDH By OPD Pendukung  : '.$opd->nama_opd.' ',
            'data' => $visi
        ]);
    }

}



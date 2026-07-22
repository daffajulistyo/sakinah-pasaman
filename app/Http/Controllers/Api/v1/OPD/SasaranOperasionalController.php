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
use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\MASTER\MasterOpd;



class SasaranOperasionalController extends Controller
{
    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->tujuan_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan OPD not Found',
                ], 422);
            }

            // cek existing tujuan pemda
            $tujuan = TujuanOpd::find($request->tujuan_opd_id);
            if (!$tujuan) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Tujuan OPD not found.',
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

           
            

            if($request->parent_id !=0){

                if(!Str::isUuid($request->parent_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Parent ID Sasaran not Found',
                    ], 422);
                }

                
                // jika parent ID dari sasaran
                $cek_parent = SasaranOpd::find($request->parent_id);
                if(!$cek_parent){
                    return response()->json([
                        'success' => false,
                        'message' => 'Perangkat Daerah not found.',
                    ], 404);
                }
            }
            

            $cek_sasaran = TujuanOpd::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $request->tujuan_opd_id)
                        ->count();

                if ($cek_sasaran <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

            $visi = BaseController::getVisiByTujuanOpdID($request->tujuan_opd_id);
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
                "order"                    => "required|integer",          
                "sasaran"                  => "required|string",                                                    
                "sasaran_operasional_id"   => "required|string",                                                    
                "is_active"                => "required|boolean",
            ]);
            $form['tujuan_opd_id'] = $request->tujuan_opd_id;
            $form['parent_id'] = $request->parent_id;
            $form['master_opd_id'] = $master_opd_id;
            $form['pohon_kinerja_visi_id'] = $visi_id;
            $form['is_sasaran_operasional'] = true;
            $form['sasaran_operasional_id'] = $request->sasaran_operasional_id;

            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->attributes->get('payload')->username;
            
            // insert into table db
            $data = SasaranOpd::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Sasaran perangkat daerah.',
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
            $master_opd_id = $request->attributes->get('payload')->opd->id;
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran Opd not Found',
                ], 422);
            }
             // cek existing tujuan
            $sasaran = SasaranOpd::find($id);
            if (!$sasaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran Opd not found.',
                ], 404);
            }
            if(!Str::isUuid($request->tujuan_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan Opd not Found',
                ], 422);
            }

           // cek existing tujuan pemda
           $tujuan_opd = TujuanOpd::find($request->tujuan_opd_id);
           if (!$tujuan_opd) {
               // jika data tidak ditamukan di dalam database
               return response()->json([
                   'success' => false,
                   'message' => 'Tujuan OPD not found.',
               ], 404);
           }

           // cek existing opd
           if(!Str::isUuid($master_opd_id)){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Id, Opd not Found',
            ], 422);
          }

           $opd = MasterOpd::find($master_opd_id);
           if (!$opd) {
               // jika data tidak ditamukan di dalam database
               return response()->json([
                   'success' => false,
                   'message' => 'Perangkat Daerah not found.',
               ], 404);
           }

           if($request->parent_id !=0){
                if(!Str::isUuid($request->parent_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Parent ID Sasaran not Found',
                    ], 422);
                }

                
                // jika parent ID dari sasaran
                $cek_parent = SasaranOpd::find($request->parent_id);
                if(!$cek_parent){
                    return response()->json([
                        'success' => false,
                        'message' => 'Perangkat Daerah not found.',
                    ], 404);
                }
           }

           $cek_sasaran = TujuanOpd::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $request->tujuan_opd_id)
                        ->count();

                if ($cek_sasaran <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }
            
            $visi = BaseController::getVisiByTujuanOpdID($request->tujuan_opd_id);
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
               "sasaran"      => "required|string",                                                  
                "sasaran_operasional_id"   => "required|string",   
               "is_active"      => "required|boolean",
           ]);

           $form['tujuan_opd_id'] = $request->tujuan_opd_id;
           $form['master_opd_id'] = $master_opd_id;
           $form['parent_id'] = $request->parent_id;
           $form['is_sasaran_operasional'] = true;
           $form['pohon_kinerja_visi_id'] = $visi_id;
           $form['sasaran_operasional_id'] = $request->sasaran_operasional_id;
           $form['updated_by'] = $request->attributes->get('payload')->username;

            $sasaran->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Sasaran OPD updated successfully.',
                'data' => $sasaran,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    public function read(Request $request, $id)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan not Found',
                ], 422);
            }

            // cek data ke database
            $sasaran = SasaranOpd::find($id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran not found.',
                ], 404);
            }

            
           $cek_sasaran = SasaranOpd::where('master_opd_id', '=', $master_opd_id)
                    ->where('id', '=', $id)
                    ->count();

            if ($cek_sasaran <= 0 ) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
            ], 404);
            }
            
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Sasaran found.',
                'data' => $sasaran,
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

            $master_opd_id = $request->attributes->get('payload')->opd->id;

            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
            $sasaran = SasaranOpd::find($id);
            if (!$sasaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran OPD not found.',
                ], 404);
            }

            $cek_sasaran = SasaranOpd::where('master_opd_id', '=', $master_opd_id)
                ->where('id', '=', $id)
                ->count();

            if ($cek_sasaran <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

             /*-------------- Cek Jika digunakan didata lain ----------------*/     
              
            $cek_sub = DB::table('sasaran_opd')->where('parent_id', $id)->count();
            if ($cek_sub > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena memiliki sub sasaran',
                ], 404);
            }

            $cek_indikator = DB::table('indikator_opd')->where('sasaran_opd_id', $id)->count();
            if ($cek_indikator > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data indikator',
                ], 404);
            }

            $pengampu = DB::table('pengampu_indikator_opd')->where('sasaran_opd_id', $id)->count();
            if ($pengampu > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran Tidak Bisa Dihapus karena sudah memiliki Pengampu',
                ], 404);
            }
            /*-------------- Cek Jika digunakan didata lain ----------------*/

            
            $sasaran->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Sasaran OPD deleted successfully.',
                'data' => $sasaran,
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
        $tujuan_opd_id = $request->get('tujuan_opd_id');
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        
        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 1000);
        $search = $request->get('search', '');
        $totalPage = 0;
        $totalRecord = 0;
        try {

            $query = SasaranOpd::query();
            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
            if(isset($tujuan_opd_id))
                $query->where('tujuan_opd_id', "=", $tujuan_opd_id);

            if(isset($master_opd_id))
                $query->where('master_opd_id', "=", $master_opd_id);

            $query->where('is_sasaran_operasional', true);

            $query->orderBy('order', 'asc');
            $objData = $query->paginate($perPage);
            $totalPage = $objData->lastPage();
            $totalRecord = $objData->total();

            // remap
            $objData = $objData->map(function($item)  use ($request)
            {
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
                    "tujuan_opd_id" => $item->tujuan_opd_id,
                    "parent_id" => $item->parent_id,
                    "order" => $item->order,
                    "sasaran" => $item->sasaran,
                    "is_active" => $item->is_active,
                    "sasaran_operasional_id" => $item->sasaran_operasional_id,
                    "created_at" => $created_at
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Sasaran Operasional',
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
                'message' => 'List of Pohon Kinerja Sasaran OPD',
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

    public function sasaran_operasional_ref()
    {
        try {
            //code...
            $data = DB::table('ref_sasaran_operasional')->get();
            return response()->json([
                'success' => true,
                'message' => 'Reference data for Sasaran Operasional',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reference data for Sasaran Operasional',
                'errors' => $th->getMessage()
            ], 500);
        }
    }
    
}

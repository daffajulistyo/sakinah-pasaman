<?php

namespace App\Http\Controllers\Api\v1\OPD;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;


class IndikatorOperasionalController extends Controller
{
    public function create(Request $request)
    {
        try {
            

            $master_opd_id = $request->get('payload')->opd->id;


            /*------------ cek validasi Tujuan-------------------------------------*/
                // cek validasi jika id berformat uuid atau tidak
                if(!Str::isUuid($request->tujuan_opd_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Tujuan not Found',
                    ], 422);
                }
                // cek existing tujuan
                $tujuan = TujuanOpd::find($request->tujuan_opd_id);
                if (!$tujuan) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'tujuan not found.',
                    ], 404);
                }


                $cek_tujuan = TujuanOpd::where('master_opd_id', '=', $master_opd_id)
                            ->where('id', '=', $request->tujuan_opd_id)
                            ->count();

                    if ($cek_tujuan <= 0 ) {
                        // jika data tidak ditamukan di dalam database
                        return response()->json([
                            'success' => false,
                            'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                    ], 404);
                }
               
            /*------------ cek validasi Tujuan-------------------------------------*/

            /*------------ cek validasi Sasaran-------------------------------------*/                
            // cek validasi jika id berformar uuid atau tidak
                if(!Str::isUuid($request->sasaran_opd_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Sasaran not Found '.$request->sasaran_opd_id.' ',
                    ], 422);
                }
                // cek existing sasaran
                $sasaran = SasaranOpd::find($request->sasaran_opd_id);
                if (!$sasaran) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'Sasaran not found.',
                    ], 404);
                }
            
            /*------------ cek validasi Sasaran-------------------------------------*/
            

            $visi = BaseController::getCurrentVisi();
            $visi_id = !empty($visi->id) ?  $visi->id : '';

            
            $form['id'] = Str::uuid();

            //validasi payload
            $form = $request->validate([
                "order"     => "required|integer",
                "indikator" => "required|string",
                "is_active" => "required|boolean"
            ]);

            
            $form['tujuan_opd_id'] = $request->tujuan_opd_id;
            $form['sasaran_opd_id'] = $request->sasaran_opd_id;
            $form['master_opd_id'] = $master_opd_id;
            $form['pohon_kinerja_visi_id'] = $visi_id;
            $form['satuan_id'] = $request->satuan_id;

            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['is_tujuan'] = $request->is_tujuan;
            $form['is_indikator_kinerja_utama'] =false;
            $form['is_tujuan'] =false;
            $form['diampu_tim'] = false;            
            $form['created_by'] = $request->get('payload')->username;
            
            
            // insert into table db
            $data = IndikatorOpd::create($form); 
            
        
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created indikator pohon kinerja kepala daerah.',
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
                    'message' => 'Invalid Id, indikator Opd not Found',
                ], 422);
            }
             // cek existing tujuan
            $indikator = IndikatorOpd::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator Opd not found.',
                ], 404);
            }

            if(!Str::isUuid($request->tujuan_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan Opd not Found',
                ], 422);
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

            $cek_sasaran = SasaranOpd::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $request->sasaran_opd_id)
                        ->count();

                if ($cek_sasaran <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Sasaran Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
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

           $cek_tujuan = TujuanOpd::where('master_opd_id', '=', $master_opd_id)
                            ->where('id', '=', $request->tujuan_opd_id)
                            ->count();

                    if ($cek_tujuan <= 0 ) {
                        // jika data tidak ditamukan di dalam database
                        return response()->json([
                            'success' => false,
                            'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                    ], 404);
                }
           
            
            $visi = BaseController::getCurrentVisi();
            $visi_id = !empty($visi->id) ?  $visi->id : '';

          
           //validasi payload
           $form = $request->validate([
               "order"          => "required|integer",              
               "indikator"      => "required|string",
               "is_active"      => "required|boolean",
           ]);

           

           $form['tujuan_opd_id'] = $request->tujuan_opd_id;
           $form['master_opd_id'] = $master_opd_id;
           $form['pohon_kinerja_visi_id'] = $visi_id;
           $form['updated_by'] = $request->get('payload')->username;

           $form['is_indikator_kinerja_utama'] = false;
           $form['diampu_tim'] = false;
           $form['is_tujuan'] =false;

            $indikator->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Indikator Sasaran OPD updated successfully.',
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


    public function read(Request $request,$id)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan not Found',
                ], 422);
            }

            // cek data ke database
            $indikator = IndikatorOpd::find($id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }

            $cek_sasaran = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $id)
                        ->count();

                if ($cek_sasaran <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Indikator Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'indikator found.',
                'data' => $indikator,
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

    public function delete($id, Request $request)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;

            $cek_sasaran = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $id)
                        ->count();

                if ($cek_sasaran <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Indikator Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, indikator not Found',
                ], 422);
            }
            $indikator = IndikatorOpd::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator OPD not found.',
                ], 404);
            }


            /*-------------- Cek Jika digunakan didata lain ----------------*/
             $pengampu = DB::table('pengampu_indikator_opd')->where('indikator_opd_id', $id)->count();
            if ($pengampu > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Indikator Tidak Bisa Dihapus karena sudah memiliki Pengampu',
                ], 404);
            }
            /*-------------- Cek Jika digunakan didata lain ----------------*/


            $indikator->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'indikator OPD deleted successfully.',
                'data' => $indikator,
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
        //////
        $searchColumn = collect(['sasaran']);        
        $sasaran_opd_id = $request->get('sasaran_opd_id');
        $master_opd_id = $request->get('payload')->opd->id;
        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $totalPage = 0;
        $totalRecord = 0;

        $opd = MasterOpd::find($master_opd_id);
        if (!$opd) {
            // jika data tidak ditamukan di dalam database
            return response()->json([
                'success' => false,
                'message' => 'Perangkat Daerah not found.',
            ], 404);
        }
        
        try {

            $query = IndikatorOpd::query();

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
            $query->where('master_opd_id', "=", $master_opd_id);
            $query->where('sasaran_opd_id', "=", $sasaran_opd_id);
            $query->where('is_tujuan',  false);            

            $query->orderBy('order', 'asc');
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
                    "tujuan_opd_id" => $item->tujuan_opd_id,
                    "sasaran_opd_id" => $item->sasaran_opd_id,
                    "master_opd_id" => $item->master_opd_id,
                    "indikator" => $item->indikator,
                    "order" => $item->order,
                    "is_active" => $item->is_active,
                    "is_indikator_kinerja_utama" => $item->is_indikator_kinerja_utama,
                    "is_tujuan" => $item->is_tujuan,
                    "created_at" => $created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Indikator OPD '.$opd->nama_opd.' asd ',
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
                'message' => 'List of Indikator OPD',
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

    public function indikator_operasional_ref($id,Request $request)
    {
        try {
            $indikator = DB::table('ref_indikator_operasional');
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran Operasional not Found',
                ], 422);
            }
            $data = $indikator->where('ref_sasaran_operasional_id', $id)->get();
            return response()->json([
                'success' => true,
                'message' => 'Reference data for Indikator Operasional',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Indikator OPD Ref',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
     }

}

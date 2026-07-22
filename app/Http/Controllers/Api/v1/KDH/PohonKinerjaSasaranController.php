<?php

namespace App\Http\Controllers\Api\v1\KDH;


use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Sakip\KDH\PohonKinerjaTujuan;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\OpdPendukungIndikator;

class PohonKinerjaSasaranController extends Controller
{
    /** service to create Sasaran */

    public function create(Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_tujuan_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan not Found',
                ], 422);
            }
            // cek existing misi
            $tujuan = PohonKinerjaTujuan::find($request->pohon_kinerja_tujuan_id);
            if (!$tujuan) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'tujuan not found.',
                ], 404);
            }
            //validasi payload
            $form = $request->validate([
                "order" => "required|integer",
                "sasaran" => "required|string",
                "is_active" => "required|boolean"
            ]);
            $form['pohon_kinerja_tujuan_id'] = $request->pohon_kinerja_tujuan_id;

            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['parent_id'] = 0;
            $form['created_by'] = $request->attributes->get('payload')->username;
            
            // insert into table db
            $data = PohonKinerjaSasaran::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created sasaran pohon kinerja kepala daerah.',
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

     /** service to read Sasaran */
    public function read($id)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
            // cek data ke database
            $sasaran = PohonKinerjaSasaran::find($id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'sasaran found.',
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

    /**
     * service for Update data Sasaran
     */
    public function update($id, Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
             // cek existing tujuan
            $sasaran = PohonKinerjaSasaran::find($id);
            if (!$sasaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran not found.',
                ], 404);
            }

            // cek validasi jika tujuan id berformat uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_tujuan_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan not Found',
                ], 422);
            }
            // cek existing misi
            $tujuan = PohonKinerjaTujuan::find($request->pohon_kinerja_tujuan_id);
            if (!$tujuan) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Tujuan not found.',
                ], 404);
            }

            $form = $request->validate([
                "order" => "required|integer",
                "sasaran" => "required|string",
                "is_active" => "required|boolean"
            ]);
            $form['parent_id'] = 0;
            $form['pohon_kinerja_tujuan_id'] = $request->pohon_kinerja_tujuan_id;
            $form['updated_by'] = $request->attributes->get('payload')->username;

            $sasaran->update($form);

            return response()->json([
                'success' => true,
                'message' => 'sasaran updated successfully.',
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


    /**
     * service to Delete Sasaran
     */
    public function delete($id)
    {
        try {
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found',
                ], 422);
            }
            $sasaran = PohonKinerjaSasaran::find($id);
            if (!$sasaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'sasaran not found.',
                ], 404);
            }

             /*-------------- Cek Jika digunakan didata lain ----------------*/          
            $cek_indikator = DB::table('pohon_kinerja_indikator')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($cek_indikator > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data indikator',
                ], 404);
            }

            $cek_cascading = DB::table('cascading')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($cek_cascading > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data cascading',
                ], 404);
            }

             $cek_pk = DB::table('perjanjian_kinerja')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($cek_pk > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data Perjanjian Kinerja',
                ], 404);
            }

            $cek_pk = DB::table('perjanjian_kinerja')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($cek_pk > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data Perjanjian Kinerja',
                ], 404);
            }

            $cek_pk_program = DB::table('perjanjian_kinerja_program')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($cek_pk_program > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data Program Perjanjian Kinerja',
                ], 404);
            }

            $cek_ra = DB::table('rencana_aksi')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($cek_ra > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data Rencana Aksi',
                ], 404);
            }

            $cek_ra_langkah = DB::table('rencana_aksi_langkah')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($cek_ra_langkah > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data Program Rencana Aksi',
                ], 404);
            }

            $cek_rkpd = DB::table('rkpd')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($cek_rkpd > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data RKPD',
                ], 404);
            }

            $cek_rkpd_program = DB::table('rkpd_kegiatan')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($cek_rkpd_program > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data Program RKPD',
                ], 404);
            }

             $tujuan_opd = DB::table('tujuan_opd')->where('pohon_kinerja_sasaran_id', $id)->count();
            if ($tujuan_opd > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran Tidak Bisa Dihapus karena sedang digunakan dalam data tujuan OPD',
                ], 404);
            }


            /*-------------- Cek Jika digunakan didata lain ----------------*/


            $sasaran->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'sasaran deleted successfully.',
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


    /**
     * Show all resources from storage with pagination.
     */
    public function list(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $tujuan_id = $request->get('tujuan_id');
        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $totalPage = 0;
        $totalRecord = 0;
        try {

            $query = PohonKinerjaSasaran::query();

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
            if(isset($tujuan_id))
                $query->where('pohon_kinerja_tujuan_id', "=", $tujuan_id);

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
                    "pohon_kinerja_tujuan_id" => $item->pohon_kinerja_tujuan_id,
                    "order" => $item->order,
                    "sasaran" => $item->sasaran,
                    "is_active" => $item->is_active,
                    "created_at" => $created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Pohon Kinerja Sasaran',
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
                'message' => 'List of Pohon Kinerja Sasaran',
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

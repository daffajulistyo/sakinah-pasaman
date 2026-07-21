<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\KDH\PohonKinerjaMisi;
use App\Models\Sakip\KDH\PohonKinerjaVisi;



class PohonKinerjaMisiController extends Controller
{
    /**
     * service to create Misi
     */
    public function create(Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_visi_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Visi not Found',
                ], 422);
            }
            // cek existing visi
            $visi = PohonKinerjaVisi::find($request->pohon_kinerja_visi_id);
            if (!$visi) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Visi not found.',
                ], 404);
            }
            //validasi payload
            $form = $request->validate([
                "order" => "required|integer",
                "misi" => "required|string",
                "is_active" => "required|boolean"
            ]);
            $form['pohon_kinerja_visi_id'] = $request->pohon_kinerja_visi_id;

            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->get('payload')->username;
            
            // insert into table db
            $data = PohonKinerjaMisi::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created new misi pohon kinerja kepala daerah.',
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
    
    /**
     * service for Read data vMisi
     */
    public function read($id)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Misi not Found',
                ], 422);
            }
            // cek data ke database
            $visi = PohonKinerjaMisi::find($id);
            if (!$visi) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Misi not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Misi found.',
                'data' => $visi,
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
     * service for Update data Misi
     */
    public function update($id, Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Misi not Found',
                ], 422);
            }
             // cek existing misi
            $misi = PohonKinerjaMisi::find($id);
            if (!$misi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Misi not found.',
                ], 404);
            }

            // cek validasi jika visi id berformat uuid atau tidak
            if(!Str::isUuid($request->pohon_kinerja_visi_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Visi not Found',
                ], 422);
            }
            // cek existing visi
            $visi = PohonKinerjaVisi::find($request->pohon_kinerja_visi_id);
            if (!$visi) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Visi not found.',
                ], 404);
            }

            $form = $request->validate([
                "order" => "required|integer",
                "misi" => "required|string",
                "is_active" => "required|boolean"
            ]);
            $form['pohon_kinerja_visi_id'] = $request->pohon_kinerja_visi_id;
            $form['updated_by'] = $request->get('payload')->username;

            $misi->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Misi updated successfully.',
                'data' => $misi,
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
     * service to Delete misi
     */
    public function delete($id)
    {
        try {
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Misi not Found',
                ], 422);
            }
            $misi = PohonKinerjaMisi::find($id);
            if (!$misi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Misi not found.',
                ], 404);
            }

            /*-------------- Cek Jika digunakan didata lain ----------------*/
            $cek_tujuan = DB::table('pohon_kinerja_tujuan')->where('pohon_kinerja_misi_id', $id)->count();
            if ($cek_tujuan > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Misi Tidak Bisa Dihapus karena sedang digunakan dalam data Tujuan',
                ], 404);
            }


            $misi->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Misi deleted successfully.',
                'data' => $misi,
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
        $searchColumn = collect(['misi']);        
        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $pohon_kinerja_visi_id = $request->get('pohon_kinerja_visi_id');

        if($pohon_kinerja_visi_id==''){
            $visi = BaseController::getCurrentVisi();
            $visi_id = $visi->id;
        }
        else
            $visi_id = $pohon_kinerja_visi_id;

        $totalPage = 0;
        $totalRecord = 0;
        try {

            $query = PohonKinerjaMisi::query();

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }           
            
            $query->where('pohon_kinerja_visi_id', "=", $visi_id);

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
                    "pohon_kinerja_visi_id" => $item->pohon_kinerja_visi_id,
                    "order" => $item->order,
                    "misi" => $item->misi,
                    "is_active" => $item->is_active,
                    "created_at" => $created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Pohon Kinerja Misi',
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
                'message' => 'List of Pohon Kinerja Misi',
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

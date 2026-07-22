<?php

namespace App\Http\Controllers\Api\v1\KDH;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Sakip\KDH\PohonKinerjaVisi;

use App\Http\Controllers\Api\v1\MASTER\BaseController;

class PohonKinerjaVisiController extends Controller
{
    /**
     * service to create visi
     */
    public function create(Request $request)
    {
        try {
            //validasi payload
            $form = $request->validate([
                "period_starts" => "required|integer",
                "period_ends" => "required|integer",
                "visi" => "required|string",
                "is_active" => "required|boolean"
            ]);
            
            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->attributes->get('payload')->username;
            if($form['is_active'] == true){
                // non aktifkan semua visi yang ada
                DB::table('pohon_kinerja_visi')->update(['is_active' => false]);
            }
            
            // insert into table db
            $data = PohonKinerjaVisi::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created new visi pohon kinerja kepala daerah.',
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
     * service for Read data visi
     */
    public function read($id)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Visi not Found',
                ], 422);
            }
            // cek data ke database
            $visi = PohonKinerjaVisi::find($id);
            if (!$visi) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Visi not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Visi found.',
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
     * service for Update data visi
     */
    public function update($id, Request $request)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Visi not Found',
                ], 422);
            }
            $visi = PohonKinerjaVisi::find($id);
            if (!$visi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visi not found.',
                ], 404);
            }

            $form = $request->validate([
                "period_starts" => "required|integer",
                "period_ends" => "required|integer",
                "visi" => "required|string",
                "is_active" => "required|boolean"
            ]);
            $payload = $request->attributes->get('payload');
            $form['updated_by'] = $payload->username;
            if($form['is_active'] == true){
                // non aktifkan semua visi yang ada
                DB::table('pohon_kinerja_visi')->update(['is_active' => false]);
            }

            $visi->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Visi updated successfully.',
                'data' => $visi,
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
     * service to Delete visi
     */
    public function delete($id)
    {
        try {
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Visi not Found',
                ], 422);
            }
            $visi = PohonKinerjaVisi::find($id);
            if (!$visi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visi not found.',
                ], 404);
            }

            /*-------------- Cek Jika digunakan didata lain ----------------*/
            $cek_misi = DB::table('pohon_kinerja_misi')->where('pohon_kinerja_visi_id', $id)->count();
            if ($cek_misi > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visi Tidak Bisa Dihapus karena sedang digunakan dalam data misi',
                ], 404);
            }

             $cek_tujuan = DB::table('tujuan_opd')->where('pohon_kinerja_visi_id', $id)->count();
            if ($cek_tujuan > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Visi Tidak Bisa Dihapus karena sedang digunakan dalam tujuan OPD',
                ], 404);
            }
            /*-------------- Cek Jika digunakan didata lain ----------------*/

            
            $visi->delete();
            return response()->json([
                'success' => true,
                'message' => 'Visi deleted successfully.',
                'data' => $visi,
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
        $searchColumn = collect(['visi', 'period_starts', 'period_ends']);

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $visi = BaseController::getCurrentVisi();
        $visi_id = !empty($visi) ? $visi->id : '';

        $query = PohonKinerjaVisi::query();
        // $query->where('id', $visi_id);

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
                "period_starts" => $item->period_starts,
                "period_ends" => $item->period_ends,
                "visi" => $item->visi,
                "is_active" => $item->is_active,
                "created_at" => $created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Pohon Kinerja Visi',
            'data' => $objData,
            'pagination' => [
                'page' => $currentPage,
                'per_page' => $perPage,
                'total_records' => $totalRecord,
                'total_page' => $totalPage,
                'search' => $search
            ]
        ]);
    }


    /**
     * 
     * get All visi
     * 
     * 
     */
    public function showall()
    {
        //
        $a = [];
        $actions = [];
        //$rawActions = PohonKinerjaVisi::with(['misi'])->get();     

        $rawActions = PohonKinerjaVisi::with(['misi'])->get();     
        
        return response()->json([
            'success' => true,
            'message' => 'Visi found.',
            'actions' => $rawActions,
        ]);
    }
}

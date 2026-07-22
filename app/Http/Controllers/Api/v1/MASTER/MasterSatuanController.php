<?php

namespace App\Http\Controllers\Api\v1\MASTER;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\MASTER\MasterSatuan;

class MasterSatuanController extends Controller
{
    /**
     * service to create satuan
     */
    public function create(Request $request)
    {
        try {
            //validasi payload
            $form = $request->validate([
                "satuan"     => "required|string",
                "is_active"  => "required|boolean"
            ]);
            
            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->attributes->get('payload')->username;
            
            // insert into table db
            $data = MasterSatuan::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created new satuan.',
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
     * service for Read data satuan
     */
    public function read($id)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Satuan not Found',
                ], 422);
            }
            // cek data ke database
            $satuan = MasterSatuan::find($id);
            if (!$satuan) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'satuan not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'satuan found.',
                'data' => $satuan,
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
                    'message' => 'Invalid Id, Satuan not Found',
                ], 422);
            }
            $satuan = MasterSatuan::find($id);
            if (!$satuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'satuan not found.',
                ], 404);
            }

            $form = $request->validate([                
                "satuan" => "required|string",
                "is_active" => "required|boolean"
            ]);
            $payload = $request->attributes->get('payload');
            $form['updated_by'] = $payload->username;

            $satuan->update($form);

            return response()->json([
                'success' => true,
                'message' => 'satuan updated successfully.',
                'data' => $satuan,
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
                    'message' => 'Invalid Id, OPD not Found',
                ], 422);
            }
            $satuan = MasterSatuan::find($id);
            if (!$satuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Satuan not found.',
                ], 404);
            }
            
            $satuan->delete();
            return response()->json([
                'success' => true,
                'message' => 'satuan deleted successfully.',
                'data' => $satuan,
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
        $searchColumn = collect(['satuan', 'keterangan']);

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $is_active = $request->get('is_active', '');

        $query = MasterSatuan::query();

        if($search != ''){
            $searchColumn->map(function($item, $index) use($search, $query){
                if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                else $query->orWhere($item, 'like', '%' . $search . '%');

            });
        }
        
        if($is_active != ""){
            $query->where('is_active', filter_var($is_active, FILTER_VALIDATE_BOOLEAN));
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
                "satuan" => $item->satuan,
                "keterangan" => $item->keterangan,
                "is_active" => $item->is_active,
                "created_at" => $created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Master Data Satuan',
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

}

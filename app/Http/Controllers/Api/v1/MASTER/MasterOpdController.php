<?php

namespace App\Http\Controllers\Api\v1\MASTER;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\MASTER\MasterOpd;

class MasterOpdController extends Controller
{
    /**
     * service to create opd
     */
    public function create(Request $request)
    {
        try {
            //validasi payload
            $form = $request->validate([
                "kode_opd"   => "required|unique:master_opd",
                "nama_opd"   => "required|string",
                "simpeg_opd_id"   => "required|integer",
                "ikd_opd_id"   => "required|integer",
                "order"      => "required|integer",
                "is_active"  => "required|boolean",
                "alias_opd"  => "required|string"
            ]);
            
            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->get('payload')->username;
            
            // insert into table db
            $data = MasterOpd::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created new opd.',
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
     * service for Read data opd
     */
    public function read($id)
    {
        try {
            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, OPD not Found',
                ], 422);
            }
            // cek data ke database
            $opd = MasterOpd::find($id);
            if (!$opd) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'opd not found.',
                ], 404);
            }
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'opd found.',
                'data' => $opd,
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
     * service for Update data opd
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
            $opd = MasterOpd::find($id);
            if (!$opd) {
                return response()->json([
                    'success' => false,
                    'message' => 'opd not found.',
                ], 404);
            }

            $form = $request->validate([                
                'kode_opd'     =>'required|unique:master_opd,kode_opd,'.$id,
                "nama_opd"   => "required|string",
                "simpeg_opd_id"   => "required|integer",
                "ikd_opd_id"   => "required|integer",
                "order"      => "required|integer",
                "is_active" => "required|boolean",
                "alias_opd"  => "required|string"
            ]);

            $payload = $request->get('payload');
            $form['updated_by'] = $payload->username;

            $opd->update($form);

            return response()->json([
                'success' => true,
                'message' => 'opd updated successfully.',
                'data' => $opd,
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
     * service to Delete opd
     */
    public function delete($id)
    {
        try {
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, OPD not Found',
                ], 422);
            }
            $opd = MasterOpd::find($id);
            if (!$opd) {
                return response()->json([
                    'success' => false,
                    'message' => 'OPD not found.',
                ], 404);
            }
            
            $opd->delete();
            return response()->json([
                'success' => true,
                'message' => 'OPD deleted successfully.',
                'data' => $opd,
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
        $searchColumn = collect(['nama_opd', 'kode_opd', 'simpeg_opd_id', 'simonev_opd_id']);

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $is_active = $request->get('is_active', '');

        $query = MasterOpd::query();

        if($search != ''){
            $searchColumn->map(function($item, $index) use($search, $query){
                if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                else $query->orWhere($item, 'like', '%' . $search . '%');

            });
        }
        
        if($is_active!="")
            $query->where('is_active', $is_active);

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
                "id"            => $item->id,
                "kode_opd"      => $item->kode_opd,
                "simpeg_opd_id" => $item->simpeg_opd_id,                
                "opd_unit_id"   => $item->opd_unit_id,
                "opd_unit"      => $item->opd_unit,
                "nama_opd"      => $item->nama_opd,
                "alamat"        => $item->alamat,
                "telp"          => $item->telp,
                "website"       => $item->website,
                "email"         => $item->website,
                "order"         => $item->order,
                "is_active"     => $item->is_active,
                "created_at"    => $created_at,
                "ikd_opd_id" => $item->ikd_opd_id,
                "simonev_opd_id" => $item->simonev_opd_id ?? null,
                "alias_opd" => $item->alias_opd ?? null
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Master Data OPD',
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

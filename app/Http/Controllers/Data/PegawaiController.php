<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Data\UserSimpeg;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PegawaiController extends Controller
{
    //
    /**
     * Show all resources from storage with pagination.
     * 
     * 
     * @return \Illuminate\Http\Response and App\Models\Data\UserSakip
     * 
     */
    public function datatable(Request $request)
    {
        //
        //
        $searchColumn = collect(['users.name', 'user_simpeg.nip', 'user_simpeg.opd_nm']);

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $query = UserSimpeg::query()->select('user_simpeg.nip', 'user_simpeg.opd_nm', 'user_simpeg.jabatan_nm', 'user_simpeg.created_at', 'users.name as nama_pegawai', 'user_simpeg.id as user_simpeg_id', 'users.id as user_id')
                ->from('user_simpeg')
                ->join('users', 'users.id', '=', 'user_simpeg.user_id');
                
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
                "id" => $item->user_id,
                "id_user_simpeg" => $item->user_simpeg_id,
                "nip" => $item->nip,
                "nama_pegawai" => $item->nama_pegawai,
                "jabatan" => $item->jabatan_nm,
                "opd" => $item->opd_nm,
                "created_at" => $created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Users',
            'data' => $objData,
            'pagination' => [
                'page' => $currentPage,
                'per_page' => $perPage,
                'total_records' => $totalRecord,
                'total_page' => $totalPage
            ]
        ]);
    }
}

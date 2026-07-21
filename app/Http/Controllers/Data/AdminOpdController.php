<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Managements\Roleplay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AdminOpdController extends Controller
{
    protected $role_admin_opd = "cdb1d545-9d9b-4d0c-aa10-879c6a9919f3"; // id role admin opd
    //
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('app.data.adminopd._index');
    }

    public function update()
    {
        //
        return view('app.data.adminopd._tambah');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'user_id' => 'required',
        ];
        $validation = $request->validate($rules);

        $role_admin_opd = $this->role_admin_opd;
        $existing_admin_opd = Roleplay::where('user_id', $request->user_id)->where('role_id', $role_admin_opd)->first();
        if(!$existing_admin_opd){
            $user = User::find($request->user_id);
            if($user){
                $attachRole = [
                    [
                        "id" => Str::uuid(),
                        "role_id" => $role_admin_opd,
                        "type" => "common",
                        "created_by" => auth()->user()->username,
                        "updated_by" => auth()->user()->username,
                    ]
                ];
                $user->role()->attach($attachRole);
            }
            else{
                return response()->json([
                    'status' => false,
                    'message' => 'Akun pegawai tidak ditemukan',
                ]);
            }

        }
        
        return response()->json([
            'status' => true,
            'message' => 'Pegawai berhasil ditambahkan sebagai admin OPD',
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id,$id_roleplay)
    {
        //
        $user = User::find($user_id);
        $name = $user->name;
        $roleplay = Roleplay::find($id_roleplay);
        $roleplay->delete();

        return response()->json([
            'message' => $name.' berhasil dihapus dari daftar admin OPD',
        ]);
    }

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
        $role_admin_opd = $this->role_admin_opd;
        $searchColumn = collect(['a.name', 'a.username']);

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $query = User::query()->select('a.name','a.username','b.created_at','a.id','us.id AS user_sakip_id', 'a.is_active', 'usg.opd_nm AS opd', 'usg.jabatan_nm AS jabatan', 'b.id AS roleplay_id', 'a.id AS user_id')
                ->from('roleplay as b')
                ->join('users as a', 'a.id', '=', 'b.user_id')
                ->join('user_sakip as us', 'us.user_id', '=', 'a.id')
                ->leftJoin('user_simpeg as usg', 'usg.user_id', '=', 'a.id')
                ->where('b.role_id', '=', $this->role_admin_opd); // Admin_OPD
                
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
                "id" => $item->user_sakip_id,
                "id_roleplay" => $item->roleplay_id,
                "user_id" => $item->user_id,
                "username" => $item->username,
                "name" => $item->name,
                "jabatan" => $item->jabatan,
                "opd" => $item->opd,
                "is_active" => $item->is_active,
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

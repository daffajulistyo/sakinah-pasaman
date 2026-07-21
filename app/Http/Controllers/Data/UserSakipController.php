<?php

namespace App\Http\Controllers\Data;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Data\UserSakip;
use Illuminate\Support\Carbon;
use App\Models\Managements\Roles;
use Illuminate\Support\Facades\DB;
use App\Models\Sakip\KDH\MasterOpd;
use App\Http\Controllers\Controller;
use App\Models\Managements\Roleplay;
use Illuminate\Database\Query\JoinClause;

class UserSakipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('app.data.usersakip._index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'name' => 'required',
            'username' => 'required|unique:users|max:25',
            'password' => ['required', 'confirmed', 'min:8'],
            'roles' => 'required',
            'is_active' => 'required',
        ];
        if($request->roles === "cdb1d545-9d9b-4d0c-aa10-879c6a9919f3") // uuid role Admin_OPD
        {
            $rules['opd_id'] = 'required';
        }
        $validation = $request->validate($rules);

        $user = User::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'is_active' => $request->is_active,
        ]);

        
        $roles = collect($request->roles)->map(function($item){
            return [
                "id" => Str::uuid(),
                "role_id" => $item,
                "type" => "common",
                "created_by" => auth()->user()->username,
                "updated_by" => auth()->user()->username,
            ];
        });

        $user->role()->attach($roles);

        $userSakip = UserSakip::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'master_opd_id' => $request->opd_id ?? null,
            "created_by" => auth()->user()->username,
            "updated_by" => auth()->user()->username,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'user' => $user->load('roleplay'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserSakip $usersakip)
    {
        //
        $user = User::find($usersakip->user_id);
        $data = $user->load('roleplay');
        $data->usersakip = $usersakip;
        return response()->json([
            'status' => true,
            'message' => 'User found',
            'data' => $data,
        ]);
    }

    /**
     * Get all roles
     */

    public function roles()
    {
        //
        $roles = Roles::where(["type" => "common"])->get();

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved all roles.',
            'roles' => $roles,
        ]);
    }

    /**
     * Get all masteropd
     */

     public function masteropd()
     {
         //
         $data = MasterOpd::all();
 
         return response()->json([
             'success' => true,
             'message' => 'Successfully retrieved all master OPD.',
             'data' => $data,
         ]);
     }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserSakip $userSakip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserSakip $usersakip)
    {
        //
        $user = User::find($usersakip->user_id);
        $rules = [
            'name' => 'required',
            'username' => 'required|max:15',
            'roles' => 'required',
            'is_active' => 'required',
        ];

        $updatefield = [
            'name' => $request->name,
            'username' => $request->username,
            'is_active' => $request->is_active == "true" ? true : false,
        ];

        if($request->username != $user->username) {
            $rules['username'] = 'required|unique:users|max:15';
        }

        if($request->password != '') {
            $rules['password'] = ['required', 'confirmed', 'min:8'];
            $updatefield['password'] = bcrypt($request->password);
        }

        if($request->roles === "cdb1d545-9d9b-4d0c-aa10-879c6a9919f3") // uuid role Admin_OPD
        {
            $rules['opd_id'] = 'required';
        }
        
        $validation = $request->validate($rules);

        $nowroles = Roleplay::where('user_id', $user->id)->get();
        $nowroles = $nowroles->pluck('role_id');
        $roles = collect($request->roles);

        $newroles = $roles->diff($nowroles);
        $oldroles = $nowroles->diff($roles);

        Roleplay::whereIn('role_id', $oldroles->all())->where('user_id', $user->id)->delete();

        $save = $newroles->map(function($role) use ($user) {
            return [
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'role_id' => $role,
                'type' => 'common',
                'created_by' => auth()->user()->username,
                'updated_by' => auth()->user()->username,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        Roleplay::insert($save->all());
        $updatefield['updated_at'] = now();
        User::where('id', $user->id)->update($updatefield);
        UserSakip::where('id', $usersakip->id)->update([
            'master_opd_id' => $request->opd_id ?? null, 
            'updated_by' => auth()->user()->username, 
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserSakip $usersakip)
    {
        //
        $user = User::where(['id' => $usersakip->user_id])->first();
        $name = $user->name;
        $usersakip->delete();
        $user->roleplay()->delete();
        $user->delete();

        return response()->json([
            'message' => 'User '.$name.' deleted successfully',
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
        $searchColumn = collect(['a.name', 'a.username']);

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $query = User::query()->select('a.name','a.username','a.created_at','a.id','us.id AS user_sakip_id',DB::raw("GROUP_CONCAT(c.role_name SEPARATOR ', ') AS role_name"), 'a.is_active', 'opd.alias_opd')
                ->from('roleplay as b')
                ->join('users as a', 'a.id', '=', 'b.user_id')
                ->join('user_sakip as us', 'us.user_id', '=', 'a.id')
                ->leftJoin('master_opd as opd', 'opd.id', '=', 'us.master_opd_id')
                ->join('roles as c', function(JoinClause $join){
                    $join->on('b.role_id', '=', 'c.id');
                    $join->where('c.type', '=', 'common');
                })
                ->groupBy(['a.id', 'us.id', 'opd.alias_opd']);

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
                "username" => $item->username,
                "name" => $item->name,
                "role_name" => $item->role_name,
                "opd" => $item->alias_opd ?? null,
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

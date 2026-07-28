<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Managements\Roleplay;
use App\Models\Managements\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class BackendUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = User::with(['roleplay.roles'])
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('username', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);

        $data->getCollection()->transform(function ($item) {
            $item->assigned_roles = $item->roleplay->map(function ($rp) {
                return ['roleplay_id' => $rp->id, 'role_id' => $rp->roles->id ?? null, 'role_name' => $rp->roles->role_name ?? '-'];
            })->filter(fn($r) => in_array($r['role_name'], ['Admin_OPD', 'Admin_KDH', 'Pegawai']))->values();
            return $item;
        });

        $availableRoles = Roles::whereIn('type', ['Admin_OPD', 'Admin_KDH', 'Pegawai'])->get();
        return view('backend.user.index', compact('data', 'search', 'availableRoles'));
    }

    public function create()
    {
        $roles = Roles::whereIn('type', ['Admin_OPD', 'Admin_KDH', 'Pegawai'])->get();
        return view('backend.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:6',
        ]);
        DB::beginTransaction();
        try {
            $userId = Str::uuid();
            User::create(['id' => $userId, 'name' => $request->name, 'username' => $request->username, 'password' => Hash::make($request->password), 'is_active' => $request->boolean('is_active', true)]);
            if ($request->role_id) {
                Roleplay::create(['id' => Str::uuid(), 'user_id' => $userId, 'role_id' => $request->role_id, 'type' => 'common', 'created_by' => auth()->user()->username, 'updated_by' => auth()->user()->username]);
            }
            DB::commit();
            return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function assignRole(Request $request)
    {
        $request->validate(['user_id' => 'required|uuid|exists:users,id', 'role_id' => 'required|uuid|exists:roles,id']);
        $exists = Roleplay::where('user_id', $request->user_id)->where('role_id', $request->role_id)->exists();
        if ($exists) return redirect()->route('user.index')->with('error', 'User sudah memiliki role tersebut.');
        Roleplay::create(['id' => Str::uuid(), 'user_id' => $request->user_id, 'role_id' => $request->role_id, 'type' => 'common', 'created_by' => auth()->user()->username, 'updated_by' => auth()->user()->username]);
        return redirect()->route('user.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function removeRole($userId, $roleplayId)
    {
        $roleplay = Roleplay::where('id', $roleplayId)->where('user_id', $userId)->firstOrFail();
        $roleplay->delete();
        return redirect()->route('user.index')->with('success', 'Role berhasil dihapus.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', $user->name . ' berhasil dihapus.');
    }

    public function edit($id)
    {
        $item = User::findOrFail($id);
        return view('backend.user.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $id,
        ]);
        $data = ['name' => $request->name, 'username' => $request->username, 'is_active' => $request->boolean('is_active', true)];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->route('user.index')->with('success', 'User berhasil diupdate.');
    }
}

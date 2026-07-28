<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Managements\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('backend.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (!Auth::user()->is_active) {
                Auth::logout();
                return redirect()->route('backend.login')->with('error', 'Akun anda telah dinonaktifkan.');
            }

            $isSuperadmin = auth()->user()->thisRole && auth()->user()->thisRole->role_name === 'Superadmin';
            if (!$isSuperadmin) {
                Auth::logout();
                return redirect()->route('backend.login')->with('error', 'Akses ditolak. Hanya Superadmin yang dapat login.');
            }

            $this->checkCurrentRole();
            $request->session()->regenerate();
            session()->put('current_role', auth()->user()->thisRole->id);
            session()->put('current_role_name', auth()->user()->thisRole->role_name);

            $myroles = auth()->user()->roleplay->load('roles');
            session()->put('myroles', ['roles' => $myroles->pluck('roles')->toArray(), 'expires_at' => now()->addMinutes(5)]);

        return redirect('/backend/login');
        }

        return redirect()->route('backend.login')->with('error', 'Username atau password salah.');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/backend');
    }

    private function checkCurrentRole()
    {
        $user = auth()->user();
        $needSet = is_null($user->current_role) || $user->current_role === '';
        if (!$needSet) {
            $needSet = !Roles::where('id', $user->current_role)->exists();
        }
        if ($needSet && $user->roleplay->count() > 0) {
            User::where('id', $user->id)->update(['current_role' => $user->roleplay->first()->role_id]);
        }
    }
}

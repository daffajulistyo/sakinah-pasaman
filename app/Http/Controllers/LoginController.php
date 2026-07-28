<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Log\LogAccess;
use App\Models\Managements\Roles;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $maxAttempts = 3; // 3;
    protected $decayMinutes = 2; // 2;

    public function index()
    {
        return view('login');
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $wantsJson = $request->expectsJson() || $request->ajax();

        if (Auth::attempt($credentials)) {
            if(!Auth::user()->is_active){
                Auth::logout();
                if ($wantsJson) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your account has been disabled.',
                    ], 401);
                }
                return redirect()->route('login')->with('error', 'Your account has been disabled.');
            }

            // ponytail: hanya user dengan current_role Superadmin bisa akses Blade admin
            $isSuperadmin = auth()->user()->thisRole && auth()->user()->thisRole->role_name === 'Superadmin';
            if (!$isSuperadmin) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akses ditolak. Hanya Superadmin yang dapat login.');
            }


            $this->checkCurrentRole();
            $request->session()->regenerate();
            
            session()->put('current_role', auth()->user()->thisRole->id);
            session()->put('current_role_name', auth()->user()->thisRole->role_name);

            //set my roles
            $myroles = auth()->user()->roleplay->load('roles');
            session()->put('myroles', [
                "roles" => $myroles->pluck('roles')->toArray(), // store all owned roles
                "expires_at" => now()->addMinutes(5) // set session expire time for 5 minutes
            ]);

            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login Success',
                ]);
            }

            return redirect()->intended('/home');
        }

        if ($wantsJson) {
            return response()->json([
                'success' => false,
                'message' => 'Username or password is incorrect.',
            ], 401);
        }
        return redirect()->route('login')->with('error', 'Username or password is incorrect.');
    }
    /**
     * Logins out the user.
     * destroy session and redirect to login page
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    /**
     * check if this user has current role
     */
    private function checkCurrentRole()
    {
        $user = auth()->user();
        $needSet = is_null($user->current_role) || $user->current_role === "";

        if (!$needSet) {
            $roleExists = Roles::where("id", $user->current_role)->exists();
            $needSet = !$roleExists;
        }

        if ($needSet) {
            $this->setCurrentRole();
        }
    }
    /**
     * set current role
     */
    private function setCurrentRole()
    {
        $user = auth()->user();

        if ($user->roleplay->count() > 0) {
            $main_role = $user->roleplay->first()->role_id;
            User::where("id", $user->id)->update(['current_role' => $main_role]);
        }
    }
}

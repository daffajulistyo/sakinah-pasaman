<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Managements\Roles;
use App\Models\Managements\Roleplay;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $payloadToken = $request->get('payload');
        $thisRole = Roles::find($payloadToken->role);
        if($thisRole === null){
            return response()->json([
                "status" => false,
                "message" => "Akun tidak terdaftar",
                "error" => 'Unregistred Role'
            ],403);
        }
        if( !in_array($thisRole->role_name, $roles) ){
            return response()->json([
                "status" => false,
                "message" => "Akses ditolak",
                "error" => 'Forbidden Access'
            ],403);
        }
        $user = User::where('username', $payloadToken->username)->first();
        $assignedRole = Roleplay::where(['user_id' => $user->id, 'role_id' => $thisRole->id])->first();
        if($assignedRole === null){
            return response()->json([
                "status" => false,
                "message" => "Akun tidak terdaftar",
                "error" => 'Unassigned Role'
            ],403);
        }
        return $next($request);
    }
}

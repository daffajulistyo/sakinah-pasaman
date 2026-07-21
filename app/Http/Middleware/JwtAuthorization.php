<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\JwtAuthentication;
use App\Models\Data\UserSakip;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if($token == null){
            return response()->json([
                "status" => false,
                "message" => "Akses terbatas, silakan login terlebih dahulu",
                "error" => "Unauthorized"
            ],401);
        }
        $decodeToken = JwtAuthentication::decode($token);
        if(!$decodeToken['status']){
            return response()->json([
                "status" => false,
                "message" => "Sesi Habis Silakan Login Kembali",
                "error" => $decodeToken['message']
            ],401);
        }
        else{
            $payload = $decodeToken['decodedToken'];
            $user = User::where('username', $payload->username)->first();
            if($user === null){
                return response()->json([
                    "status" => false,
                    "message" => "Akun tidak terdaftar",
                    "error" => 'User account invalid'
                ],403);
            }
            
            $usersakip = UserSakip::where('user_id', $user->id)->first();
            if($usersakip === null){
                return response()->json([
                    "status" => false,
                    "message" => "Akun tidak terdaftar",
                    "error" => 'User Sakip account invalid'
                ],403);
            }
            if(!$user->is_active){
                return response()->json([
                    "status" => false,
                    "message" => "Akun diblock",
                    "error" => 'User account was suspended!'
                ],403);
            }
            $request->attributes->add(['payload' => $payload]);
        }
        return $next($request);
    }
}

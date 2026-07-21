<?php 

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtAuthentication {


    public static function create($payload = [])
    {
        $key = env('JWT_PASSPHRASE', 'sumbarprovoke');
        $payload['iat'] = time();
        $payload['exp'] = $payload['iat'] + (24*60*60); // 1 bulan = 30 hari x 24 jam x 60 menit x 60 detik

        $jwt = JWT::encode($payload, $key, 'HS256');

        return $jwt;
    }

    public static function decode($token)
    {
        try{
            $key = env('JWT_PASSPHRASE', 'sumbarprovoke');
            $decode = JWT::decode($token, new Key($key, 'HS256'));
            return [
                "status" => true,
                "decodedToken" => $decode
            ];
        }
        catch(\Exception $e)
        {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

}
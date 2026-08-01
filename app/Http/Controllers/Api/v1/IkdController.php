<?php

/*
 * NOTE: Controller ini DI-NONAKTIFKAN (di-comment) karena integrasi dengan
 * IKD BPKAD diganti oleh master lokal. Body di-comment untuk menghilangkan
 * client_secret IKD yang terekspos. Simpan sebagai referensi jika suatu saat
 * integrasi dengan IKD BPKAD dibutuhkan kembali.
 *
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IkdController extends Controller
{
    public function auth()
    {   
        try {

            $response = Http::withoutVerifying()
                ->withOptions(["verify"=>false])
                ->post('https://ikd-bpkad.sumbarprov.go.id/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => '3',
                'client_secret' => 'PorxW1pQYgzcKZwM3Cv1Y74Pb3qf2izDuzwD2C0P',
                'scope' => '*',
            ]);

           if($response->getStatusCode() !="200"){
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $response->getReasonPhrase()
            ];  
           }
           else
           {
            return [
                'success' => true,
                'message' => 'Wrong',
                'data' => $response
            ];  
           }
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ];
        }
    }


    public function anggaran(Request $request)
    {
       $auth = $this->auth();
       if($auth['success']==true)
       {
            $token = $auth['data']['access_token'];

            $response = Http::withoutVerifying()
                ->withOptions(["verify"=>false])
                ->withToken($token)->retry(2, 0, function (Exception $exception, PendingRequest $request) {
                    if (! $exception instanceof RequestException || $exception->response->status() !== 401) {
                        return false;
                    }                 
                    $request->withToken($this->auth()['data']['access_token']);
                 
                    return true;
                })
                ->get('https://ikd-bpkad.sumbarprov.go.id/api/anggaran/skpd-program/2560/2024');


                if($response->getStatusCode() !="200"){
                    return [
                        'success' => false,
                        'message' => 'Something went wrong!',
                        'errors' => $response->getReasonPhrase()
                    ];  
                   }
                   else
                   {
                    return [
                        'success' => true,
                        'message' => 'Data Anggaran',
                        'data' => $response['result']
                    ];  
                   }

       }
       else{
            return [
                'success' => false,
                'message' => 'Something went wrong! ',
                'errors' =>'Auth Failed'
            ]; 
       }
    }



}
*/

<?php

namespace App\Http\Controllers\Api\v1\OPD;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\OPD\Pengampu;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

class PengampuController extends Controller
{
    public function create(Request $request)
    {
        try {
                $master_opd_id = $request->attributes->get('payload')->opd->id;

                $opd = MasterOpd::find($master_opd_id);
                if (!$opd) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'OPD User not found.',
                    ], 404);
                }

            /*------------ validasi Sasaran / Indikator -------------------------------------*/

                // cek validasi jika id berformat uuid atau tidak
                if(!Str::isUuid($request->indikator_opd_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, indikator not Found',
                    ], 422);
                }
                // cek existing tujuan
                $indikator = IndikatorOpd::find($request->indikator_opd_id);
                if (!$indikator) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'indikator not found.',
                    ], 404);
                }


                $cek_indikator = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
                            ->where('id', '=', $request->indikator_opd_id)
                            ->count();

                    if ($cek_indikator <= 0 ) {
                        // jika data tidak ditamukan di dalam database
                        return response()->json([
                            'success' => false,
                            'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                    ], 404);
                }
               
                            // cek validasi jika id berformar uuid atau tidak
                if(!Str::isUuid($request->sasaran_opd_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Sasaran not Found '.$request->sasaran_opd_id.' ',
                    ], 422);
                }
                // cek existing sasaran
                $sasaran = SasaranOpd::find($request->sasaran_opd_id);
                if (!$sasaran) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'Sasaran not found.',
                    ], 404);
                }
            
            /*------------ validasi Sasaran / Indikator -------------------------------------*/
            
            /*------------ Cek Pengampu yg sudah ada -------------------------------------*/
            $cek_data_pengampu = Pengampu::where('master_opd_id', '=', $master_opd_id)
                            ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                            ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                            ->where('nip', '=', $request->nip)
                            ->where('jabatan_id', '=', $request->jabatan_id)
                        ->count();
            if ($cek_data_pengampu > 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => ' Pegawai sudah diset sebagai pengampu sasaran terkait  '.$nip.' - '.$jabatan_nm.'  ',
                ], 404);
            }
            /*------------ Cek Pengampu yg sudah ada -------------------------------------*/

            //validasi payload
            $form = $request->validate([
                "nip"     => "required|string",
                "nama" => "required|string",
                "jns_jbtn_id" => "required|string",
                "jns_jbtn_nm" => "required|string",
                "jabatan_id" => "required|string",
                "jabatan_nm" => "required|string",
                "eselon_id" => "required|string",
                "eselon_nm" => "required|string",
                "is_active" => "required|boolean"
            ]);
            $form['indikator_opd_id'] = $request->indikator_opd_id;
            $form['sasaran_opd_id'] = $request->sasaran_opd_id;
            $form['master_opd_id'] = $master_opd_id;
            $form['is_ketua'] = !empty($request->is_ketua) ? $request->is_ketua : false;

            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->attributes->get('payload')->username;
            
            // insert into table db
            $data = Pengampu::create($form); 
            
        
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created pengampu indikator OPD.',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {

            // handdle error
            return response()->json([
                "success" => false,
                "message" => "Something went wrong!",
                "errors" => $th->getMessage()
            ],500);
        }
    }


    public function update($id, Request $request)
    {
        try {
            $master_opd_id = $request->attributes->get('payload')->opd->id;

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, indikator Opd not Found',
                ], 422);
            }
             // cek existing tujuan
            $pengampu = Pengampu::find($id);
            if (!$pengampu) {
                return response()->json([
                    'success' => false,
                    'message' => 'pengampu Opd not found.',
                ], 404);
            }

            if(!Str::isUuid($request->indikator_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, indikator not Found',
                ], 422);
            }
            // cek existing tujuan
            $indikator = IndikatorOpd::find($request->indikator_opd_id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }


            $cek_indikator = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $request->indikator_opd_id)
                        ->count();

                if ($cek_indikator <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

          

           $cek_sasaran = SasaranOpd::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $request->sasaran_opd_id)
                        ->count();

                if ($cek_sasaran <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Sasaran Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

            if(!Str::isUuid($request->sasaran_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sasaran not Found '.$request->sasaran_opd_id.' ',
                ], 422);
            }
            // cek existing sasaran
            $sasaran = SasaranOpd::find($request->sasaran_opd_id);
            if (!$sasaran) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Sasaran not found.',
                ], 404);
            }


             /*------------ Cek Pengampu yg sudah ada -------------------------------------*/
             $cek_data_pengampu = Pengampu::where('master_opd_id', '=', $master_opd_id)
                    ->where('sasaran_opd_id', '=', $request->sasaran_opd_id)
                    ->where('indikator_opd_id', '=', $request->indikator_opd_id)
                    ->where('nip', '=', $request->nip)
                    ->where('jabatan_id', '=', $request->jabatan_id)
                    ->where('id', '<>', $id)
                    ->count();
                    
            if ($cek_data_pengampu > 0) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => ' Pegawai sudah diset sebagai pengampu sasaran terkait  '.$nip.'  '
                ]);

            }
            
           //validasi payload
           $form = $request->validate([
                "nip"     => "required|string",
                "nama"  => "required|string",
                "jns_jbtn_id" => "required|string",
                "jns_jbtn_nm" => "required|string",
                "jabatan_id" => "required|string",
                "jabatan_nm" => "required|string",
                "eselon_id" => "required|string",
                "eselon_nm" => "required|string",
                "is_active" => "required|boolean"
            ]);

            $form['indikator_opd_id'] = $request->indikator_opd_id;
            $form['sasaran_opd_id'] = $request->sasaran_opd_id;
            $form['master_opd_id'] = $master_opd_id;
             $form['is_ketua'] = !empty($request->is_ketua) ? $request->is_ketua : false;
            $form['updated_by'] = $request->attributes->get('payload')->username;

            $pengampu->update($form);

            return response()->json([
                'success' => true,
                'message' => 'pengampu Sasaran OPD updated successfully.',
                'data' => $pengampu,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function read(Request $request,$id)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;
            $opd = MasterOpd::find($master_opd_id);
                if (!$opd) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'OPD User not found.',
                    ], 404);
                }

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Pengampu not Found',
                ], 422);
            }

            // cek data ke database
            $pengampu = Pengampu::find($id);
            if (!$pengampu) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'pengampu not found.',
                ], 404);
            }

            $cek = Pengampu::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $id)
                        ->count();

                if ($cek <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Pengampu bukan dari OPD Terkait',
                ], 404);
            }

            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'pengampu found.',
                'data' => $pengampu,
            ]);
        } catch (\Throwable $th) {
            // handle error
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function delete($id, Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;

            $cek = Pengampu::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $id)
                        ->count();

                if ($cek <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Pengampu Bukan Dari OPD Terkait',
                ], 404);
            }

            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, indikator not Found',
                ], 422);
            }
            $pengampu = Pengampu::find($id);
            if (!$pengampu) {
                return response()->json([
                    'success' => false,
                    'message' => 'pengampu OPD not found.',
                ], 404);
            }
            $pengampu->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'pengampu OPD deleted successfully.',
                'data' => $pengampu,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {
        //
        $searchColumn = collect(['nama']);
        
        $indikator_opd_id = $request->get('indikator_opd_id');
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $search = $request->get('search', '');
        try {

            $query = Pengampu::query();
            $query->where('indikator_opd_id', "=", $indikator_opd_id);

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
            if(isset($sasaran_opd_id))
                $query->where('sasaran_opd_id', "=", $sasaran_opd_id);

            $query->orderBy('jns_jbtn_id', 'desc');
            $objData = $query->get();

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
                    "id" => $item->id,
                    "indikator_opd_id" => $item->indikator_opd_id,
                    "sasaran_opd_id" => $item->sasaran_opd_id,
                    "master_opd_id" => $item->master_opd_id,
                    "nip" => $item->nip,
                    "nama" => $item->nama,
                    "nama" => $item->nama,
                    "jns_jbtn_id" => $item->jns_jbtn_id,
                    "jns_jbtn_nm" => $item->jns_jbtn_nm,
                    "jabatan_id" => $item->jabatan_id,
                    "jabatan_nm" => $item->jabatan_nm,
                    "eselon_id" => $item->eselon_id,
                    "eselon_nm" => $item->eselon_nm,
                    "is_active" => $item->is_active,
                    "is_ketua" => $item->is_ketua,
                    "created_at" => $created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Pengampu Sasaran OPD',
                'data' => $objData
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Pengampu Sasaran OPD',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}

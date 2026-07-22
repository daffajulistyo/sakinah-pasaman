<?php

namespace App\Http\Controllers\Api\v1\OPD;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\MASTER\BaseController;

use App\Models\Sakip\OPD\TujuanOpd;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;

use Illuminate\Support\Facades\Storage;


class IndikatorOpdController extends Controller
{
    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->attributes->get('payload')->opd->id;

            /*------------ cek validasi Tujuan-------------------------------------*/
                // cek validasi jika id berformat uuid atau tidak
                if(!Str::isUuid($request->tujuan_opd_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Tujuan not Found',
                    ], 422);
                }
                // cek existing tujuan
                $tujuan = TujuanOpd::find($request->tujuan_opd_id);
                if (!$tujuan) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'tujuan not found.',
                    ], 404);
                }


                $cek_tujuan = TujuanOpd::where('master_opd_id', '=', $master_opd_id)
                            ->where('id', '=', $request->tujuan_opd_id)
                            ->count();

                    if ($cek_tujuan <= 0 ) {
                        // jika data tidak ditamukan di dalam database
                        return response()->json([
                            'success' => false,
                            'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                    ], 404);
                }
               
            /*------------ cek validasi Tujuan-------------------------------------*/
            /*------------ cek validasi Sasaran-------------------------------------*/
                
            if($request->sasaran_opd_id !='' OR $request->sasaran_opd_id !=null OR $request->sasaran_opd_id !=0)
            {
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
            }
            /*------------ cek validasi Sasaran-------------------------------------*/


            $visi = BaseController::getVisiByTujuanOpdID($request->tujuan_opd_id);
            $visi_id =  $visi[0]->visi_id;
            if (!$visi ) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Visi Not Found',
                ], 404);
            }

                     
            //validasi payload
            $form = $request->validate([
                "order"     => "required|integer",
                "indikator" => "required|string",
                "is_active" => "required|boolean",
                //"satuan_id" => "required|string",
                //"target_1" => "required",
                //"target_2" => "required",
                //"target_3" => "required",
                //"target_4" => "required",
                //"target_5" => "required"
            ]);

            
            $form['tujuan_opd_id'] = $request->tujuan_opd_id;
            $form['sasaran_opd_id'] = $request->sasaran_opd_id;
            $form['master_opd_id'] = $master_opd_id;
            $form['pohon_kinerja_visi_id'] = $visi_id;
            $form['satuan_id'] = !empty($request->satuan_id) ? $request->satuan_id : NULL;
            $form['target_1'] = !empty($request->target_1) ? $request->target_1 : '';
            $form['target_2'] = !empty($request->target_2) ? $request->target_2 : '';
            $form['target_3'] = !empty($request->target_3) ? $request->target_3 : '';
            $form['target_4'] = !empty($request->target_4) ? $request->target_4 : '';
            $form['target_5'] = !empty($request->target_5) ? $request->target_5 : '';
            $form['target_6'] = !empty($request->target_6) ? $request->target_6 : '';
            $form['baseline'] = !empty($request->rilis) ? $request->baseline : '';
            $form['sumber_data'] = !empty($request->sumber_data) ? $request->sumber_data : '';
            $form['defenisi'] = !empty($request->defenisi) ? $request->defenisi : '';
            $form['kegunaan'] = !empty($request->kegunaan) ? $request->kegunaan : '';
            $form['rilis'] = !empty($request->rilis) ? $request->rilis : '';


            if ($request->hasFile('formula_perhitungan')) {
                    
                    $request->validate([    
                        "formula_perhitungan" => "required|mimes:jpeg,png,jpg,pdf"
                    ]);
                    
                    $file = $request->file('formula_perhitungan');

                    // 'uploads' is the directory within your MinIO bucket
                    $path = Storage::disk('s3')->put('formula/opd/'.$master_opd_id .'/', $file); 
                    $form['formula_perhitungan']    = $path;
            }


            // create uuid and assign author
            $form['id'] = Str::uuid();
            $form['is_tujuan'] = $request->is_tujuan;
            $form['is_indikator_kinerja_utama'] = !empty($request->is_indikator_kinerja_utama) ? $request->is_indikator_kinerja_utama : false;
             $form['diampu_tim'] = !empty($request->diampu_tim) ? $request->diampu_tim : false;
            $form['parent_id'] = 0;
            $form['created_by'] = $request->attributes->get('payload')->username;
            $form['diampu'] = !empty($request->is_indikator_kinerja_utama) ? $request->is_indikator_kinerja_utama : false;
            
            // insert into table db
            $data = IndikatorOpd::create($form); 
            
        
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created indikator pohon kinerja kepala daerah.',
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
            $indikator = IndikatorOpd::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator Opd not found.',
                ], 404);
            }

            if(!Str::isUuid($request->tujuan_opd_id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan Opd not Found',
                ], 422);
           }

           // cek existing tujuan pemda
           $tujuan_opd = TujuanOpd::find($request->tujuan_opd_id);
           if (!$tujuan_opd) {
               // jika data tidak ditamukan di dalam database
               return response()->json([
                   'success' => false,
                   'message' => 'Tujuan OPD not found.',
               ], 404);
           }

           $cek_tujuan = TujuanOpd::where('master_opd_id', '=', $master_opd_id)
                            ->where('id', '=', $request->tujuan_opd_id)
                            ->count();

                    if ($cek_tujuan <= 0 ) {
                        // jika data tidak ditamukan di dalam database
                        return response()->json([
                            'success' => false,
                            'message' => ' Tujuan Bukan Di Ampu Oleh OPD Terkait',
                    ], 404);
                }
                
           // cek existing opd
           if(!Str::isUuid($master_opd_id)){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Id, Opd not Found',
            ], 422);
          }

           $opd = MasterOpd::find($master_opd_id);
           if (!$opd) {
               // jika data tidak ditamukan di dalam database
               return response()->json([
                   'success' => false,
                   'message' => 'Perangkat Daerah not found.',
               ], 404);
           }

          /* if($request->parent_id !=0){
                if(!Str::isUuid($request->parent_id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Parent ID Sasaran not Found',
                    ], 422);
                }

                
                // jika parent ID dari sasaran
                $cek_parent = SasaranOpd::find($request->parent_id);
                if(!$cek_parent){
                    return response()->json([
                        'success' => false,
                        'message' => 'Perangkat Daerah not found.',
                    ], 404);
                }
           }*/
           
           if($request->is_tujuan==false){
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
           }

           
            
            $visi = BaseController::getVisiByTujuanOpdID($request->tujuan_opd_id);
            $visi_id =  $visi[0]->visi_id;
            if (!$visi ) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'Visi Not Found',
                ], 404);
            }

          
           //validasi payload
           $form = $request->validate([
               "order"          => "required|integer",              
               "indikator"      => "required|string",
               "is_active"      => "required|boolean",
           ]);


           $form['satuan_id'] = !empty($request->satuan_id) ? $request->satuan_id : NULL;
            $form['target_1'] = !empty($request->target_1) ? $request->target_1 : '';
            $form['target_2'] = !empty($request->target_2) ? $request->target_2 : '';
            $form['target_3'] = !empty($request->target_3) ? $request->target_3 : '';
            $form['target_4'] = !empty($request->target_4) ? $request->target_4 : '';
            $form['target_5'] = !empty($request->target_5) ? $request->target_5 : '';
            $form['target_6'] = !empty($request->target_6) ? $request->target_6 : '';
            $form['baseline'] = !empty($request->rilis) ? $request->baseline : '';
            $form['sumber_data'] = !empty($request->sumber_data) ? $request->sumber_data : '';
            $form['defenisi'] = !empty($request->defenisi) ? $request->defenisi : '';
            $form['kegunaan'] = !empty($request->kegunaan) ? $request->kegunaan : '';
            $form['rilis'] = !empty($request->rilis) ? $request->rilis : '';

           $form['tujuan_opd_id'] = $request->tujuan_opd_id;
           $form['sasaran_opd_id'] = $request->sasaran_opd_id;
           $form['master_opd_id'] = $master_opd_id;
           $form['pohon_kinerja_visi_id'] = $visi_id;
           $form['updated_by'] = $request->attributes->get('payload')->username;

             $form['is_indikator_kinerja_utama'] = !empty($request->is_indikator_kinerja_utama) ? $request->is_indikator_kinerja_utama : false;
             $form['diampu_tim'] = !empty($request->diampu_tim) ? $request->diampu_tim : false;

            $indikator->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Indikator Sasaran OPD updated successfully.',
                'data' => $indikator,
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

            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Tujuan not Found',
                ], 422);
            }

            // cek data ke database
            $indikator = IndikatorOpd::find($id);
            if (!$indikator) {
                // jika data tidak ditamukan di dalam database
                return response()->json([
                    'success' => false,
                    'message' => 'indikator not found.',
                ], 404);
            }

            $cek_sasaran = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $id)
                        ->count();

                if ($cek_sasaran <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Indikator Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'indikator found.',
                'data' => $indikator,
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

            $cek_sasaran = IndikatorOpd::where('master_opd_id', '=', $master_opd_id)
                        ->where('id', '=', $id)
                        ->count();

                if ($cek_sasaran <= 0 ) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => ' Indikator Bukan Di Ampu Oleh OPD Terkait',
                ], 404);
            }

            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, indikator not Found',
                ], 422);
            }
            $indikator = IndikatorOpd::find($id);
            if (!$indikator) {
                return response()->json([
                    'success' => false,
                    'message' => 'indikator OPD not found.',
                ], 404);
            }


            /*-------------- Cek Jika digunakan didata lain ----------------*/

             $cek_pk = DB::table('pk_opd')
                     ->where('indikator_opd_id', $id)
                     ->where('deleted_at', '=', NULL)
                     ->count();

            if ($cek_pk > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                    'errors' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan di Perjanjian Kinerja',
                ], 409);
            }

            $cek_ra = DB::table('rencana_opd')
                     ->where('indikator_opd_id', $id)
                     ->where('deleted_at', '=', NULL)
                     ->count();

            if ($cek_ra > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                    'errors' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan di Rencana Aksi',
                ], 409);
            }

            $cek_ra_langkah = DB::table('rencana_opd_langkah')
                            ->where('indikator_opd_id', $id)
                             ->where('deleted_at', '=', NULL)
                             ->count();
            if ($cek_ra_langkah > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                    'errors' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan di Program Rencana Aksi',
                ], 409);
            }

            $cek_rkpd = DB::table('renja')
                        ->where('indikator_opd_id', $id)
                        ->where('deleted_at', '=', NULL)
                        ->count();
            if ($cek_rkpd > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                    'errors' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan di data RENJA OPD',
                ], 409);
            }

            $pengampu = DB::table('pengampu_indikator_opd')
                    ->where('indikator_opd_id', $id)
                     ->where('deleted_at', '=', NULL)
                    ->count();

            if ($pengampu > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                    'errors' => 'Indikator Tidak Bisa Dihapus karena sudah memiliki Pengampu',
                ], 409);
            }

            $skp = DB::table('skp_indikator')
                    ->where('sasaran_opd_id', $id)
                    ->where('deleted_at', '=', NULL)
                    ->count();
                    
            if ($skp > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                    'errors' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan di Indikator SKP',
                ], 409);
            }

            $skp = DB::table('skp_langkah')
                    ->where('indikator_opd_id', $id)
                    ->where('deleted_at', '=', NULL)
                    ->count();
            if ($skp > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                    'errors' => 'Indikator Tidak Bisa Dihapus karena sedang digunakan di Langkah SKP',
                ], 409);
            }
            /*-------------- Cek Jika digunakan didata lain ----------------*/


            $indikator->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'indikator OPD deleted successfully.',
                'data' => $indikator,
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
        //////
        $searchColumn = collect(['sasaran']);
        $tujuan_opd_id = $request->get('tujuan_opd_id');
        $sasaran_opd_id = $request->get('sasaran_opd_id');
        $master_opd_id = $request->attributes->get('payload')->opd->id;
        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $totalPage = 0;
        $totalRecord = 0;

        $opd = MasterOpd::find($master_opd_id);
        if (!$opd) {
            // jika data tidak ditamukan di dalam database
            return response()->json([
                'success' => false,
                'message' => 'Perangkat Daerah not found.',
            ], 404);
        }
        
        try {

            $query = IndikatorOpd::query();

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            
            $query->where('tujuan_opd_id', "=", $tujuan_opd_id);
            $query->where('master_opd_id', "=", $master_opd_id);

            if(!empty($sasaran_opd_id)){
                $query->where('sasaran_opd_id', "=", $sasaran_opd_id);
                $query->where('is_tujuan',  false);
            }else{
                $query->where('is_tujuan', true);
            }            

            $query->orderBy('order', 'asc');
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
                    "id" => $item->id,
                    "tujuan_opd_id" => $item->tujuan_opd_id,
                    "sasaran_opd_id" => $item->sasaran_opd_id,
                    "master_opd_id" => $item->master_opd_id,
                    "indikator" => $item->indikator,
                    "order" => $item->order,
                    "is_active" => $item->is_active,
                    "is_indikator_kinerja_utama" => $item->is_indikator_kinerja_utama,
                    "is_tujuan" => $item->is_tujuan,
                    "diampu_tim" => $item->diampu_tim,
                    "created_at" => $created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Indikator OPD '.$opd->nama_opd.' asd ',
                'data' => $objData,
                'pagination' => [
                    'page' => $currentPage,
                    'per_page' => $perPage,
                    'total_records' => $totalRecord,
                    'total_page' => $totalPage,
                    'search' => $search
                ]
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Indikator OPD',
                'data' => [],
                'pagination' => [
                    'page' => $currentPage,
                    'per_page' => $perPage,
                    'total_records' => $totalRecord,
                    'total_page' => $totalPage,
                    'search' => $search
                ],
                'errors' => $th->getMessage()
            ], 500);
        }
    }


    public function upload($id, Request $request)
    {

        $master_opd_id = $request->attributes->get('payload')->opd->id;
        try {
            /*------------ cek validasi Indikator-------------------------------------*/
                // cek validasi jika id berformar uuid atau tidak
                if(!Str::isUuid($id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Indikator not Found',
                    ], 422);
                }
                // cek existing tujuan
                $indikator = indikatorOpd::find($id);
                if (!$indikator) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'Indikator not found.',
                    ], 404);
                }
            /*------------ cek validasi Indikator-------------------------------------*/
            
            if ($request->hasFile('formula_perhitungan')) {
                    //validasi payload
                    $form = $request->validate([
                        "formula_perhitungan" => "required|mimes:jpeg,png,jpg,pdf"
                    ]);                    
                    $file = $request->file('formula_perhitungan');

                    // 'uploads' is the directory within your MinIO bucket
                    $path = Storage::disk('s3')->put('formula/opd'.$master_opd_id.'/', $file); 

                    if(!empty($indikator->formula_perhitungan)){
                        if (Storage::disk('s3')->has($indikator->formula_perhitungan)) {                               
                            Storage::disk('s3')->delete($indikator->formula_perhitungan);
                        }
                    }

                    $form['formula_perhitungan']    = $path;
            }
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'Gambar updated unsuccessfully.',
                    'errors' => 'Gambar Wajib Diisi',
                ]);
            }

            $form['updated_by'] = $request->attributes->get('payload')->username;

            $indikator->where('id', $id)->update($form);

            return response()->json([
                'success' => true,
                'message' => 'indikator updated successfully.',
                'data' => $indikator,
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

    public function preview($id)
    {
        try {
            /*------------ cek validasi Indikator-------------------------------------*/
                // cek validasi jika id berformar uuid atau tidak
                if(!Str::isUuid($id)){
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Id, Indikator not Found',
                    ], 422);
                }
                // cek existing tujuan
                $indikator = indikatorOpd::find($id);
                if (!$indikator) {
                    // jika data tidak ditamukan di dalam database
                    return response()->json([
                        'success' => false,
                        'message' => 'Indikator not found.',
                    ], 404);
                }
            /*------------ cek validasi Indikator-------------------------------------*/
            
            $objectName = $indikator->formula_perhitungan; // or image.jpg, video.mp4
            

            if (!Storage::disk('s3')->exists($objectName)) {
                return response()->json([
                    'success' => true,
                    'message' => 'File Loaded unsuccessfully.',
                    'errors' => 'File Not Found',
                ]);
            }
            

            $path = !empty($indikator) ? $indikator->formula_perhitungan : '';
            $disk = Storage::disk('s3');

            if (!$disk->exists($path)) {
                abort(404);
            }

            $stream = $disk->get($path);
            $mime = $disk->mimeType($path);

            return response($stream, 200)->header('Content-Type', $mime);

        } catch (\Throwable $th) {

            // handdle error
            return response()->json([
                "success" => false,
                "message" => "Something went wrong!",
                "errors" => $th->getMessage()
            ],500);
        }
        
    }

}

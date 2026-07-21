<?php

namespace App\Http\Controllers\Api\v1\Pegawai;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Sakip\MASTER\MasterSatuan;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Http\Controllers\Api\v1\MASTER\BaseController;
use App\Models\Sakip\OPD\PeriodeSkp;
use App\Models\Sakip\OPD\LangkahSkp;
use App\Models\Sakip\OPD\SasaranOpd;
use App\Models\Sakip\OPD\IndikatorOpd;
use App\Models\Sakip\OPD\IndikatorSkp;

use Illuminate\Support\Collection;
use App\Models\Sakip\Services\UserSimpeg;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Sakip\OPD\Atasan;

class SkpController extends Controller
{
   
    public function list(Request $request)
    {
        $master_opd_id = $request->get('payload')->opd->id;
        $username = $request->get('payload')->username;
        $nip = $request->get('payload')->nip;
        $jabatan_id = $request->get('payload')->jabatan_id;

         // cek existing opd
         $opd = MasterOpd::find($master_opd_id);
         if (!$opd) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Perangkat Daerah not found.',
             ], 404);
         }

        $PeriodeSkp = PeriodeSkp::where('created_by', '=', $username)
                    ->where('nip', '=', $nip)
                    ->get();
       
        $PeriodeSkp = $PeriodeSkp->map(function($dl) use($request) 
        {
            return [
                "id"             => $dl->id,
                "nip"            => $dl->nip,
                "periode_awal"   => $dl->periode_awal,
                "periode_akhir"  => $dl->periode_akhir,
                "tahun"          => $dl->tahun,
                "pendekatan"     => $dl->pendekatan,
                "jns_jbtn_nm"    => $dl->jns_jbtn_nm,
                "jabatan_nm"     => $dl->jabatan_nm,
                "is_active"     => $dl->is_active,
                "batas_input"     => $dl->batas_input 
            ];
        });               


        return response()->json([
            'success' => true,
            'message' => 'Daftar Sasaran Kinerja Pegawai  '.$username.' ',
            'data' => $PeriodeSkp,
        ]);
    }


    public function create(Request $request)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;
            $username = $request->get('payload')->username;
            $nip = $request->get('payload')->nip;
            $jns_jbtn_id = $request->get('payload')->jns_jbtn_id;
            $jns_jbtn_nm = $request->get('payload')->jns_jbtn_nm;
            $jabatan_id  = $request->get('payload')->jabatan_id;
            $jabatan_nm  = $request->get('payload')->jabatan_nm;
            $eselon_id   = $request->get('payload')->eselon_id;
            $eselon_nm   = $request->get('payload')->eselon_nm;
            $nip         = $request->get('payload')->nip;
            $periode_awal= $request->periode_awal;
            $periode_akhir= $request->periode_akhir;

            $tahun = substr($periode_awal, 0, 4);            
            $tahun_akhir = substr($periode_akhir, 0, 4); 
            
            if($tahun != $tahun_akhir){
                return response()->json([
                    'success' => false,
                    'message' => 'Periode Awal SKP tidak Sesuai Dengan Periode Akhir SKP ',
                ], 404);
            }

               //validasi payload
            $form = $request->validate([
                "periode_awal" => "required|date",
                "periode_akhir" => "required|date",
                "pendekatan" => "required|string"
            ]);

            $form['periode_awal'] = $request->periode_awal;
            $form['periode_akhir'] = $request->periode_akhir;
            $form['pendekatan'] = $request->pendekatan;
            $form['tahun'] = $tahun;
            $form['is_active'] = true;

            
            $form['nip'] = $nip;
            $form['master_opd_id']  = $master_opd_id;
            $form['jns_jbtn_id']    = $jns_jbtn_id;
            $form['jns_jbtn_nm']    = $jns_jbtn_nm;
            $form['jabatan_id']     = $jabatan_id;
            $form['jabatan_nm']     = $jabatan_nm;
            $form['eselon_id']      = $eselon_id;
            $form['eselon_nm']      = $eselon_nm;
            $form['id'] = Str::uuid();
            $form['created_by'] = $request->get('payload')->username;
            

            $tahun = substr($periode_awal, 0, 4);

            $cek = PeriodeSkp::where('nip', '=', $nip)
                              ->where('master_opd_id', '=', $master_opd_id)
                              ->whereYear('periode_awal', '=', $tahun)
                              ->count();

            if ($cek > 0) {
             // jika data tidak ditamukan di dalam database
             return response()->json([
                 'success' => false,
                 'message' => 'Periode SKP '.$tahun.' '.$nip.' Sudah Ada',
             ], 404);
            }
        
            // insert into table db
            $data = PeriodeSkp::create($form);
            
            // return response json
            return response()->json([
                'success' => true,
                'message' => 'Successfully created Periode SKP Pegawai.',
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


    public function read(Request $request,$id)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;
            $nip = $request->get('payload')->nip;
            $username = $request->get('payload')->username;


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, SKP not Found',
                ], 422);
            }


            $detail_skp = PeriodeSkp::where('id', '=', $id)
                        ->where('nip', '=', $nip)
                        ->where('created_by', '=', $username)
                        ->first();

            if(!$detail_skp){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid, SKP not Found',
                ], 422);
            }

            $sasaran_pegawai = IndikatorSkp::where('skp_id', '=', $id)
                             ->where('created_by', '=', $username)
                             ->select('sasaran_opd_id')
                            ->pluck('sasaran_opd_id')
                            ->toArray();

                   

            $sasaran_atasan = SasaranOpd::whereIn('id', $sasaran_pegawai)
                                ->distinct()
                                ->select('parent_id')
                                ->pluck('parent_id')                                
                                ->toArray();


            $sasaran = SasaranOpd::whereIn('id', $sasaran_atasan)->get();
           
            $sasaran = $sasaran->map(function($item) use($request, $id) 
            {
                $skp_id = $request->skp_id;
                $username = $request->get('payload')->username;

                 $sasaran_pegawai = SasaranOpd::join('skp_indikator', 'skp_indikator.sasaran_opd_id', '=', 'sasaran_opd.id')
                                ->where('skp_indikator.skp_id', '=', $id)
                                ->where('sasaran_opd.parent_id', '=', $item->id)
                                ->where('skp_indikator.created_by', '=', $username)
                                ->whereNull('skp_indikator.deleted_at')
                                ->distinct()
                                ->get(['sasaran_opd.id as id_indikator_opd', 'sasaran_opd.sasaran', 'sasaran_opd.order']);

                    $sasaran_pegawai = $sasaran_pegawai->map(function($item) use($request, $id) 
                    {
                        $username = $request->get('payload')->username;
                         $indikator_pegawai = IndikatorOpd::join('skp_indikator', 'skp_indikator.indikator_opd_id', '=', 'indikator_opd.id')
                                ->where('skp_indikator.skp_id', '=', $id)
                                ->where('skp_indikator.sasaran_opd_id', '=', $item->id_indikator_opd)
                                ->where('skp_indikator.created_by', '=', $username)
                                ->whereNull('skp_indikator.deleted_at')
                                ->distinct()
                                ->get(['indikator_opd.id as indikator_opd_id', 'indikator_opd.indikator', 'indikator_opd.order', 'skp_indikator.id', 'skp_indikator.target', 'skp_indikator.satuan']);

                                  $indikator_pegawai = $indikator_pegawai->map(function($item) use($request, $id) 
                                  {

                                        return [
                                        "id"       => $item->id,
                                        "indikator_opd_id"       => $item->indikator_opd_id,
                                        "indikator"  => $item->indikator, 
                                        "target"  => $item->target, 
                                        "satuan"  => $item->satuan, 
                                    ];
                                });   

                        return [
                            "id"        => $item->id_indikator_opd,
                            "sasaran"   => $item->sasaran,
                            "indikator" => $indikator_pegawai
                        ];
                    });    

                    return [
                        "id"       => $item->id,
                        "sasaran"  => $item->sasaran,
                        "sasaran_pegawai" => $sasaran_pegawai
                    ];
            });    

            
            

            $data_skp = new Collection([
                'id' => $detail_skp->id, 
                'nip' => $detail_skp->nip, 
                'master_opd_id'=> $detail_skp->master_opd_id, 
                'periode_awal' => $detail_skp->periode_awal, 
                'periode_akhir' => $detail_skp->periode_akhir, 
                'tahun' => $detail_skp->tahun, 
                'pendekatan' => $detail_skp->pendekatan, 
                'is_active' => $detail_skp->is_active, 
                'jns_jbtn_nm' => $detail_skp->jns_jbtn_nm, 
                'jabatan_nm' => $detail_skp->jabatan_nm, 
                'eselon_nm' => $detail_skp->eselon_nm, 
                'created_at' => $detail_skp->created_at, 
                'sasaran_atasan' => $sasaran
            ]);
            
            // return data jika data ditemukan
            return response()->json([
                'success' => true,
                'message' => 'Skp Found.',
                'data' => $data_skp,
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


    public function generate_pdf(Request $request,$id)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;
            $nip = $request->get('payload')->nip;
            $username = $request->get('payload')->username;


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, SKP not Found',
                ], 422);
            }


            $detail_skp = PeriodeSkp::where('id', '=', $id)
                        ->where('nip', '=', $nip)
                        ->where('created_by', '=', $username)
                        ->first();

            if(!$detail_skp){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid, SKP not Found',
                ], 422);
            }

            $sasaran_pegawai = IndikatorSkp::where('skp_id', '=', $id)
                             ->where('created_by', '=', $username)
                             ->select('sasaran_opd_id')
                             ->pluck('sasaran_opd_id')
                             ->toArray();
              //  print_r($sasaran_pegawai);
                            

                   

            $sasaran_atasan = SasaranOpd::whereIn('id', $sasaran_pegawai)
                                ->distinct()
                                ->select('parent_id')
                                ->pluck('parent_id')                                
                                ->toArray();

            
            $sasaran = SasaranOpd::whereIn('id', $sasaran_atasan)->distinct()->get();

           
            $sasaran = $sasaran->map(function($item) use($request, $id) 
            {
                $skp_id = $request->skp_id;
                $username = $request->get('payload')->username;

                 $sasaran_pegawai = SasaranOpd::join('skp_indikator', 'skp_indikator.sasaran_opd_id', '=', 'sasaran_opd.id')
                                ->where('skp_indikator.skp_id', '=', $id)
                                ->where('sasaran_opd.parent_id', '=', $item->id)
                                ->where('skp_indikator.created_by', '=', $username)
                                ->where('skp_indikator.deleted_at', NULL)
                                ->distinct()
                                ->get(['sasaran_opd.id as id_indikator_opd', 'sasaran_opd.sasaran', 'sasaran_opd.order']);


                    $sasaran_pegawai = $sasaran_pegawai->map(function($item) use($request, $id) 
                    {
                         $username = $request->get('payload')->username;
                         $indikator_pegawai = IndikatorOpd::join('skp_indikator', 'skp_indikator.indikator_opd_id', '=', 'indikator_opd.id')
                                ->where('skp_indikator.skp_id', '=', $id)
                                ->where('skp_indikator.sasaran_opd_id', '=', $item->id_indikator_opd)
                                ->where('skp_indikator.created_by', '=', $username)
                                ->where('skp_indikator.deleted_at', NULL)
                                ->distinct()
                                ->get(['indikator_opd.id as indikator_opd_id', 'indikator_opd.indikator', 'indikator_opd.order', 'skp_indikator.id', 'skp_indikator.target_tw1', 'skp_indikator.target_tw2', 'skp_indikator.target_tw3', 'skp_indikator.target_tw4', 'skp_indikator.satuan']);

                                  $indikator_pegawai = $indikator_pegawai->map(function($item) use($request, $id) 
                                  {

                                        $langkah = LangkahSkp::where('skp_id', '=', $id)
                                                ->where('indikator_skp_id', '=', $item->id)
                                                ->get();

                                        $langkah = $langkah->map(function($item) use($request, $id) 
                                        {
                                            return [
                                                "id"       => $item->id,
                                                "langkah"       => $item->langkah
                                               /* "target_tw1"  => $item->target_tw1, 
                                                "target_tw2"  => $item->target_tw4, 
                                                "target_tw3"  => $item->target_tw3, 
                                                "target_tw4"  => $item->target_tw4, 
                                                "realisasi_tw1"  => $item->realisasi_tw1, 
                                                "realisasi_tw2"  => $item->realisasi_tw2, 
                                                "realisasi_tw3"  => $item->realisasi_tw3, 
                                                "realisasi_tw4"  => $item->realisasi_tw4, 
                                                "satuan"  => $item->satuan,
                                                "keterangan"  => $item->keterangan*/
                                            ];
                                        });

                                        return [
                                        "id"       => $item->id,
                                        "indikator_opd_id"       => $item->indikator_opd_id,
                                        "indikator"  => $item->indikator, 
                                        "target_tw1"  => $item->target_tw1, 
                                        "target_tw2"  => $item->target_tw2, 
                                        "target_tw3"  => $item->target_tw3, 
                                        "target_tw4"  => $item->target_tw4, 
                                        "satuan"  => $item->satuan, 
                                        "langkah"  => $langkah
                                    ];
                                });   

                        return [
                            "id"        => $item->id_indikator_opd,
                            "sasaran"   => $item->sasaran,
                            "indikator" => $indikator_pegawai
                        ];
                    });    

                    return [
                        "id"       => $item->id,
                        "sasaran"  => $item->sasaran,
                        "sasaran_pegawai" => $sasaran_pegawai
                    ];
            });    


            $data_skp = new Collection([
                'id' => $detail_skp->id, 
                'nip' => $detail_skp->nip, 
                'master_opd_id'=> $detail_skp->master_opd_id, 
                'periode_awal' => $detail_skp->periode_awal, 
                'periode_akhir' => $detail_skp->periode_akhir, 
                'tahun' => $detail_skp->tahun, 
                'pendekatan' => $detail_skp->pendekatan, 
                'is_active' => $detail_skp->is_active, 
                'jns_jbtn_nm' => $detail_skp->jns_jbtn_nm, 
                'jabatan_nm' => $detail_skp->jabatan_nm, 
                'eselon_nm' => $detail_skp->eselon_nm, 
                'created_at' => $detail_skp->created_at, 
                'sasaran_atasan' => $sasaran
            ]);
            
            $profile = User::join('user_simpeg', 'user_simpeg.user_id', '=', 'users.id')
                            ->where('users.username', $nip)->first();

            $atasan = Atasan::where('nip_pegawai', '=', $nip)->first();

            $data = [
                'generated_at' => now()->toDateTimeString(),
                'profil' => $profile,
                'atasan' => $atasan,
                'data_skp' => $data_skp
            ];

            /*return response()->json([
                'success' => false,
                'message' => $data
            ], 200);
            die;*/

           $pdf = Pdf::loadView('report_template.pegawai.skp', compact('data'))
                        ->setPaper('Legal', 'landscape');
           return $pdf->download('SKP.pdf');
          
        } catch (\Throwable $th) {
            // handle error
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

     public function generate_pdf_realisasi(Request $request,$id)
    {
        try {

            $master_opd_id = $request->get('payload')->opd->id;
            $nip = $request->get('payload')->nip;
            $username = $request->get('payload')->username;


            // cek validasi jika id berformar uuid atau tidak
            if(!Str::isUuid($id)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, SKP not Found',
                ], 422);
            }


            $detail_skp = PeriodeSkp::where('id', '=', $id)
                        ->where('nip', '=', $nip)
                        ->where('created_by', '=', $username)
                        ->first();

            if(!$detail_skp){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid, SKP not Found',
                ], 422);
            }

            $sasaran_pegawai = IndikatorSkp::where('skp_id', '=', $id)
                             ->where('created_by', '=', $username)
                             ->select('sasaran_opd_id')
                             ->pluck('sasaran_opd_id')
                             ->toArray();
              //  print_r($sasaran_pegawai);
                            

                   

            $sasaran_atasan = SasaranOpd::whereIn('id', $sasaran_pegawai)
                                ->distinct()
                                ->select('parent_id')
                                ->pluck('parent_id')                                
                                ->toArray();

            
            $sasaran = SasaranOpd::whereIn('id', $sasaran_atasan)->distinct()->get();

           
            $sasaran = $sasaran->map(function($item) use($request, $id) 
            {
                $skp_id = $request->skp_id;
                $username = $request->get('payload')->username;

                 $sasaran_pegawai = SasaranOpd::join('skp_indikator', 'skp_indikator.sasaran_opd_id', '=', 'sasaran_opd.id')
                                ->where('skp_indikator.skp_id', '=', $id)
                                ->where('sasaran_opd.parent_id', '=', $item->id)
                                ->where('skp_indikator.created_by', '=', $username)
                                ->whereNull('skp_indikator.deleted_at')
                                ->distinct()
                                ->get(['sasaran_opd.id as id_indikator_opd', 'sasaran_opd.sasaran', 'sasaran_opd.order']);


                    $sasaran_pegawai = $sasaran_pegawai->map(function($item) use($request, $id) 
                    {
                         $username = $request->get('payload')->username;
                         $indikator_pegawai = IndikatorOpd::join('skp_indikator', 'skp_indikator.indikator_opd_id', '=', 'indikator_opd.id')
                                ->where('skp_indikator.skp_id', '=', $id)
                                ->where('skp_indikator.sasaran_opd_id', '=', $item->id_indikator_opd)
                                ->where('skp_indikator.created_by', '=', $username)
                                ->whereNull('skp_indikator.deleted_at')
                                ->distinct()
                                ->get(['indikator_opd.id as indikator_opd_id', 'indikator_opd.indikator', 'indikator_opd.order', 'skp_indikator.id', 'skp_indikator.target_tw1', 'skp_indikator.target_tw2', 'skp_indikator.target_tw3', 'skp_indikator.target_tw4',  'skp_indikator.realisasi_tw1', 'skp_indikator.realisasi_tw2',  'skp_indikator.realisasi_tw3', 'skp_indikator.realisasi_tw4', 'skp_indikator.capaian_tw1', 'skp_indikator.capaian_tw2', 'skp_indikator.capaian_tw3', 'skp_indikator.capaian_tw4', 'skp_indikator.hambatan', 'skp_indikator.tindak_lanjut',     'skp_indikator.satuan']);

                                  $indikator_pegawai = $indikator_pegawai->map(function($item) use($request, $id) 
                                  {

                                        $langkah = LangkahSkp::where('skp_id', '=', $id)
                                                ->where('indikator_skp_id', '=', $item->id)
                                                ->get();

                                        $langkah = $langkah->map(function($item) use($request, $id) 
                                        {
                                            return [
                                                "id"       => $item->id,
                                                "langkah"       => $item->langkah,
                                                "target_tw1"  => $item->target_tw1, 
                                                "target_tw2"  => $item->target_tw4, 
                                                "target_tw3"  => $item->target_tw3, 
                                                "target_tw4"  => $item->target_tw4, 
                                                "realisasi_tw1"  => $item->realisasi_tw1, 
                                                "realisasi_tw2"  => $item->realisasi_tw2, 
                                                "realisasi_tw3"  => $item->realisasi_tw3, 
                                                "realisasi_tw4"  => $item->realisasi_tw4, 
                                                "satuan"  => $item->satuan,
                                                "keterangan"  => $item->keterangan
                                            ];
                                        });

                                        return [
                                        "id"       => $item->id,
                                        "indikator_opd_id"       => $item->indikator_opd_id,
                                        "indikator"  => $item->indikator, 
                                        "target_tw1"  => $item->target_tw1, 
                                        "target_tw2"  => $item->target_tw2, 
                                        "target_tw3"  => $item->target_tw3, 
                                        "target_tw4"  => $item->target_tw4, 
                                        "realisasi_tw1"  => $item->realisasi_tw1, 
                                        "realisasi_tw2"  => $item->realisasi_tw3, 
                                        "realisasi_tw3"  => $item->realisasi_tw3, 
                                        "realisasi_tw4"  => $item->realisasi_tw4, 
                                        "capaian_tw1"  => $item->capaian_tw1, 
                                        "capaian_tw2"  => $item->capaian_tw2, 
                                        "capaian_tw3"  => $item->capaian_tw3, 
                                        "capaian_tw4"  => $item->capaian_tw4, 
                                        "hambatan"  => $item->hambatan, 
                                        "tindak_lanjut"  => $item->tindak_lanjut, 
                                        "satuan"  => $item->satuan, 
                                        "langkah"  => $langkah
                                    ];
                                });   

                        return [
                            "id"        => $item->id_indikator_opd,
                            "sasaran"   => $item->sasaran,
                            "indikator" => $indikator_pegawai
                        ];
                    });    

                    return [
                        "id"       => $item->id,
                        "sasaran"  => $item->sasaran,
                        "sasaran_pegawai" => $sasaran_pegawai
                    ];
            });    


            $data_skp = new Collection([
                'id' => $detail_skp->id, 
                'nip' => $detail_skp->nip, 
                'master_opd_id'=> $detail_skp->master_opd_id, 
                'periode_awal' => $detail_skp->periode_awal, 
                'periode_akhir' => $detail_skp->periode_akhir, 
                'tahun' => $detail_skp->tahun, 
                'pendekatan' => $detail_skp->pendekatan, 
                'is_active' => $detail_skp->is_active, 
                'jns_jbtn_nm' => $detail_skp->jns_jbtn_nm, 
                'jabatan_nm' => $detail_skp->jabatan_nm, 
                'eselon_nm' => $detail_skp->eselon_nm, 
                'created_at' => $detail_skp->created_at, 
                'sasaran_atasan' => $sasaran
            ]);
            
            $profile = User::join('user_simpeg', 'user_simpeg.user_id', '=', 'users.id')
                            ->where('users.username', $nip)->first();

            $atasan = Atasan::where('nip_pegawai', '=', $nip)->first();

            $data = [
                'generated_at' => now()->toDateTimeString(),
                'profil' => $profile,
                'atasan' => $atasan,
                'data_skp' => $data_skp
            ];

            /*return response()->json([
                'success' => false,
                'message' => $data
            ], 200);
            die;*/

           $pdf = Pdf::loadView('report_template.pegawai.skp_realisasi', compact('data'))
                        ->setPaper('Legal', 'landscape');
           return $pdf->download('SKP.pdf');
          
        } catch (\Throwable $th) {
            // handle error
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}

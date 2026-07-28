<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Api\v1\Dashboard\BaseController;

use App\Models\Sakip\KDH\PohonKinerjaVisi;
use App\Models\Sakip\KDH\PohonKinerjaMisi;
use App\Models\Sakip\KDH\PohonKinerjaTujuan;
use App\Models\Sakip\KDH\PohonKinerjaSasaran;
use App\Models\Sakip\KDH\PohonKinerjaIndikator;
use App\Models\Sakip\KDH\Rkpd;
use App\Models\Sakip\KDH\PerjanjianKinerja;
use App\Models\Sakip\KDH\PerjanjianKinerjaProgram;
use App\Models\Sakip\KDH\RencanaAksi;
use App\Models\Sakip\KDH\RencanaAksiProgram;
use App\Models\Sakip\KDH\RencanaAksiLangkah;
use App\Models\Sakip\KDH\OpdPendukungIndikator;
use App\Models\Sakip\KDH\Cascading;
use App\Models\Sakip\KDH\RkpdKegiatan;


class PemdaController extends BaseController
{
    public function visi()
    {
        try 
        {
            $visi = $this->getCurrentVisi();
            return response()->json([
                'success' => true,
                'message' => 'Visi Aktif',
                'data' => $visi,
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Sasaran dan OPD Pendukung',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function pohonkinerja()
    {
        //
        $a = [];
        $actions = [];

        $visi = $this->getCurrentVisi();
        $visi_id = $visi->id;
        
        $rawActions = PohonKinerjaVisi::with(
            ['misi' => 
                function($query) {
                     $query->where('is_active', true);                 
                     $query->with([
                        'tujuan' => 
                        function($query) {
                            $query->where('is_active', true);                 
                            $query->with([
                               'sasaran' => 
                               function($query) {
                                   $query->where('is_active', true);                 
                                   $query->orderBy('order', 'asc');                 
                                   $query->with([
                                      'indikator_sasaran'=>
                                      function($query){
                                        $query->where('is_active', true); 
                                        $query->orderBy('order', 'asc');                 
                                        $query->with(['opd_pendukung'=>
                                                function($query){
                                                    $query->select('master_opd.id', 'master_opd.nama_opd', 'master_opd.kode_opd');
                                                }
                                            ]);
                                      },
                                      'cascading' =>
                                      function($query){
                                        $query->orderBy('order', 'asc');                 
                                        $query->where('is_active', true); 
                                      }
                                   ]);
                               },

                               'indikator_tujuan' => 
                               function($query) {
                                    $query->orderBy('order', 'asc');                 
                                   $query->where('is_active', true);                                                   
                                   $query->where('is_tujuan', true);                                                   
                               }
                               
                               
                            ]);
                        } 
                     ]);
                }            
        ])->where('is_active', '=', true)
        ->where('id', $visi_id)
        ->get();

       
        return response()->json([
            'success' => true,
            'message' => 'Pohon Kinerja',
            'data' => $rawActions,
        ]);
    }    

    public function cascading(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);        
        $search = $request->get('search', '');
        
        try {

            $tujuan = $this->getTujuanPemda();
            
            $query = PohonKinerjaSasaran::query();
            $query->whereIn('pohon_kinerja_tujuan_id', $tujuan);

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            $query->orderBy('created_at', 'desc');
            $objData = $query->get();

            $id_sasaran = $objData->pluck('id');

            

           /* $indikator = PohonKinerjaIndikator::with(['opd_pendukung' => function ($query) {
                $query->select('opd_pendukung_indikator.id as id', 'nama_opd', 'website', 'simpeg_opd_id', 'ikd_opd_id');
             }])->whereIn('pohon_kinerja_sasaran_id', $id_sasaran)->get();
            */
            

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


               /* $indikator = PohonKinerjaIndikator::where('pohon_kinerja_sasaran_id', '=', $item->pohon_kinerja_sasaran_id)->get();
                    
                $id_indikator = $indikator->pluck('id'); */
                $indikator = PohonKinerjaIndikator::with(['opd_pendukung' => function ($query) {
                    $query->select('opd_pendukung_indikator.id as id', 'nama_opd', 'website', 'simpeg_opd_id', 'ikd_opd_id');
                 }])->where('pohon_kinerja_sasaran_id', $item->id)->get();

                 $id_indikator = $indikator->pluck('id');
                
                 $opd = OpdPendukungIndikator::join('master_opd', 'master_opd.id', '=', 'opd_pendukung_indikator.master_opd_id')
                 ->whereIn('pohon_kinerja_indikator_id', $id_indikator)
                 //->groupBy('opd_pendukung_indikator.master_opd_id, master_opd.kode_opd')
                 ->distinct()
                ->get(['opd_pendukung_indikator.master_opd_id', 'master_opd.kode_opd', 'master_opd.nama_opd' , 'master_opd.simpeg_opd_id', 'master_opd.ikd_opd_id',]);

                $program = Cascading::where('pohon_kinerja_sasaran_id', $item->id)->get();
                return [
                    "id" => $item->id,
                    "pohon_kinerja_tujuan_id" => $item->pohon_kinerja_tujuan_id,
                    "order" => $item->order,
                    "sasaran" => $item->sasaran,
                    "opd_pendukung" => $opd,
                    "program" => $program,
                    "is_active" => $item->is_active,
                    "created_at" => $created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Sasaran dan OPD Pendukung',
                'data' => $objData
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Sasaran dan OPD Pendukung',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function rkpd(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        $murni = $request->get('murni');
        $tahun = $request->get('tahun');

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 1000);
        $search = $request->get('search', '');

       

        $totalPage = 0;
        $totalRecord = 0;
        try {

            $tujuan = $this->getTujuanPemda();

            $query = PohonKinerjaSasaran::query();
           
            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }

            $query->whereIn('pohon_kinerja_tujuan_id', $tujuan);

            if(isset($sasaran_id))
                $query->where('id', $sasaran_id);
            $query->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id');
            $query->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id');
            $query->select('pohon_kinerja_sasaran.*');
            $query->orderBy('pohon_kinerja_misi.order', 'asc');
            $query->orderBy('pohon_kinerja_tujuan.order', 'asc');
            $query->orderBy('pohon_kinerja_sasaran.order', 'asc');
            $objData = $query->paginate($perPage);
            $totalPage = $objData->lastPage();
            $totalRecord = $objData->total();

            // remap
            $objData = $objData->map(function($item) use($request) {
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }
                
                $indikator = PohonKinerjaIndikator::where('is_tujuan', false)
                                          ->where('pohon_kinerja_sasaran_id', $item->id)
                                          ->orderBy('order', 'asc')
                                          ->get();

                $program = RkpdKegiatan::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=', $request->tahun)
                ->where('murni', '=', $request->murni)
                ->get();
                
                $program = $program->map(function($dp) use($request) 
                {
                    return $list_kegiatan = [
                        "id"            => $dp->id,
                        "pohon_kinerja_sasaran_id"    => $dp->pohon_kinerja_sasaran_id,
                        "list_kegiatan"    => json_decode($dp->list_kegiatan),
                        "tahun"    => $dp->tahun,
                        "anggaran"    => $dp->anggaran,                        
                        "is_active"     => $dp->is_active,
                        "murni"     => $dp->murni
                    ];
                });


                $indikator = $indikator->map(function($ik) use($request) 
                {   
                    $murni = $request->get('murni');
                    
                    /*if($murni)
                    $tahun = date('Y') + 1;
                    else
                    $tahun = date('Y');*/
                    $tahun = $request->tahun;
                    $indikator_id = $ik->id;

                    $rkpd = Rkpd::where('pohon_kinerja_indikator_id', $ik->id)
                            ->where('tahun', $tahun)
                            ->where('murni', $murni)
                            ->get();

                    return $listindikator = [
                        "id"        => $ik->id,
                        "indikator" => $ik->indikator,
                        "rkpd"      => $rkpd,
                        "target_rpjmd" => $this->getTargetRpjmd($tahun, $indikator_id),
                    ];
                });


                $anggaran = RkpdKegiatan::where('pohon_kinerja_sasaran_id', $item->id)
                ->where('tahun', $request->tahun)
                ->where('murni', $request->murni)
                ->limit(1)
                ->sum('anggaran');

                //$anggaran = $anggaran->pluck('anggaran');
                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "indikator" =>$indikator,
                    "program" =>$program,
                    "anggaran" => $anggaran
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of RKPD',
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
                'message' => 'List of RKPD Sasaran',
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

    public function perjanjiankinerja(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 1000);
        $search = $request->get('search', '');

        $totalPage = 0;
        $totalRecord = 0;
        try {

            $tujuan = $this->getTujuanPemda();
            $query = PohonKinerjaSasaran::query();
           
            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            $query->whereIn('pohon_kinerja_tujuan_id', $tujuan);

            if(isset($sasaran_id))
                $query->where('id', $sasaran_id);
            $query->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id');
            $query->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id');
            $query->select('pohon_kinerja_sasaran.*');
            $query->orderBy('pohon_kinerja_misi.order', 'asc');
            $query->orderBy('pohon_kinerja_tujuan.order', 'asc');
            $query->orderBy('pohon_kinerja_sasaran.order', 'asc');
            $objData = $query->paginate($perPage);
            $totalPage = $objData->lastPage();
            $totalRecord = $objData->total();

            // remap
            $objData = $objData->map(function($item) use($request) {
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }
                
                $indikator = PohonKinerjaIndikator::where('is_tujuan', false)
                                          ->where('pohon_kinerja_sasaran_id', $item->id)
                                          ->get();
                
                $program = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=', $request->get('tahun'))
                ->where('murni', '=', $request->get('murni'))
                ->get();

                $program = $program->map(function($dp) use($request) 
                {
                    return $list_kegiatan = [
                        "id"            => $dp->id,
                        "pohon_kinerja_sasaran_id"    => $dp->pohon_kinerja_sasaran_id,
                        "list_kegiatan"    => json_decode($dp->list_kegiatan),
                        "tahun"    => $dp->tahun,
                        "anggaran"    => $dp->anggaran,                        
                        "is_active"     => $dp->is_active,
                        "murni"     => $dp->murni
                    ];
                });

                $anggaran = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=', $request->get('tahun'))
                ->where('murni', '=', $request->get('murni'))
                ->sum('anggaran');
             
                $indikator = $indikator->map(function($ik) use($request) 
                {
                    $perjanjian_kinerja = PerjanjianKinerja::where('pohon_kinerja_indikator_id', $ik->id)
                            ->where('tahun', '=',$request->get('tahun'))
                            ->where('murni', '=', $request->get('murni'))
                            ->get(['id', 'target', 'murni', 'tahun'])
                            ->first();

                    return $listindikator = [
                        "id"                 => $ik->id,
                        "indikator"          => $ik->indikator,
                        "perjanjian_kinerja" => $perjanjian_kinerja
                    ];
                });

                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "list_program" => $program,
                    "order" => $item->order,
                    "indikator" =>$indikator,
                    "anggaran" =>$anggaran
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Perjanjian Kinerjaah',
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
                'message' => 'List of Perjanjian Kinerjaah',
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

    public function rencana(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 1000);
        $search = $request->get('search', '');

        $totalPage = 0;
        $totalRecord = 0;
        try {


            $tujuan = $this->getTujuanPemda();

            $query = PohonKinerjaSasaran::query();
            $query->whereIn('pohon_kinerja_tujuan_id', $tujuan);

            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            if(isset($sasaran_id))
                $query->where('id', $sasaran_id);
            $query->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id');
            $query->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id');
            $query->select('pohon_kinerja_sasaran.*');
            $query->orderBy('pohon_kinerja_misi.order', 'asc');
            $query->orderBy('pohon_kinerja_tujuan.order', 'asc');
            $query->orderBy('pohon_kinerja_sasaran.order', 'asc');
            $objData = $query->paginate($perPage);
            $totalPage = $objData->lastPage();
            $totalRecord = $objData->total();

            // remap
            $objData = $objData->map(function($item) use($request) {
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }
                
                $indikator = PohonKinerjaIndikator::where('is_tujuan', '=', false)
                                          ->where('pohon_kinerja_sasaran_id','=', $item->id)
                                          ->get();
                
                $anggaran_murni = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=',$request->tahun)
                ->where('murni', '=',true)
                ->sum('anggaran');

                $anggaran_perubahan = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=',$request->tahun)
                ->where('murni', '=',false)
                ->sum('anggaran');

                $indikator = $indikator->map(function($ik) use($request) 
                {
                    $rencana_aksi = RencanaAksi::where('pohon_kinerja_indikator_id', '=',$ik->id)
                            ->where('tahun', '=', $request->tahun)
                            ->get(['id', 'target_tw1', 'target_tw2', 'target_tw3', 'target_tw4', 'tahun'])
                            ->first();

                    $langkah = RencanaAksiLangkah::where('pohon_kinerja_indikator_id', '=', $ik->id)
                               ->where('tahun', '=',$request->tahun)
                               ->get();  
                    $langkah = $langkah->map(function($dl) use($request) 
                    {
                        return [
                            "id"                 => $dl->id,
                            "langkah"          => $dl->langkah,
                            "target_tw1"          => $dl->target_tw1,
                            "target_tw2"          => $dl->target_tw2,
                            "target_tw3"          => $dl->target_tw3,
                            "target_tw4"          => $dl->target_tw4
                        ];
                    });                

                    $target_pk = PerjanjianKinerja::where('pohon_kinerja_indikator_id', '=', $ik->id)
                    ->where('tahun', '=',$request->tahun)
                    ->get(['target', 'tahun', 'murni']); 

                    return $listindikator = [
                        "id"                 => $ik->id,
                        "indikator"          => $ik->indikator,
                        "rencana_aksi" => $rencana_aksi,
                        "langkah" => $langkah,
                        "target_perjanjian_kinerja" => $target_pk
                    ];
                });

                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "anggaran_perjanjian_kinerja" => ['murni'=> $anggaran_murni, 'perubahan'=>$anggaran_perubahan],
                    "indikator" =>$indikator
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Rencana Aksi',
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
                'message' => 'List Rencana Aksi',
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

    public function realisasi(Request $request)
    {
        //
        $searchColumn = collect(['sasaran']);
        $sasaran_id = $request->get('sasaran_id');
        $indikator_id = $request->get('indikator_id');
        

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 1000);
        $search = $request->get('search', '');

        $totalPage = 0;
        $totalRecord = 0;
        try {


            $tujuan = $this->getTujuanPemda();
            $query = PohonKinerjaSasaran::query();           
            if($search != ''){
                $searchColumn->map(function($item, $index) use($search, $query){
                    if($index == 0) $query->where($item, 'like', '%' . $search . '%');
                    else $query->orWhere($item, 'like', '%' . $search . '%');

                });
            }
            $query->whereIn('pohon_kinerja_tujuan_id', $tujuan);
            if(isset($sasaran_id))
                $query->where('id', $sasaran_id);
            $query->join('pohon_kinerja_tujuan', 'pohon_kinerja_tujuan.id', '=', 'pohon_kinerja_sasaran.pohon_kinerja_tujuan_id');
            $query->join('pohon_kinerja_misi', 'pohon_kinerja_misi.id', '=', 'pohon_kinerja_tujuan.pohon_kinerja_misi_id');
            $query->select('pohon_kinerja_sasaran.*');
            $query->orderBy('pohon_kinerja_misi.order', 'asc');
            $query->orderBy('pohon_kinerja_tujuan.order', 'asc');
            $query->orderBy('pohon_kinerja_sasaran.order', 'asc');
            $objData = $query->get();

            // remap
            $objData = $objData->map(function($item) use($request) {
                $jam = Carbon::parse($item->created_at)->diffInHours();
                if($jam > 24) {
                    $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
                }
                else
                {
                    $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
                }
                
                $indikator = PohonKinerjaIndikator::where('is_tujuan', '=', false)
                                          ->where('pohon_kinerja_sasaran_id','=', $item->id)
                                          ->get();
                
                $anggaran_murni = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=',$request->tahun)
                ->where('murni', '=',true)
                ->sum('anggaran');

                $anggaran_perubahan = PerjanjianKinerjaProgram::where('pohon_kinerja_sasaran_id', '=', $item->id)
                ->where('tahun', '=',$request->tahun)
                ->where('murni', '=',false)
                ->sum('anggaran');

                $indikator = $indikator->map(function($ik) use($request) 
                {
                    $rencana_aksi = RencanaAksi::where('pohon_kinerja_indikator_id', '=',$ik->id)
                            ->where('tahun', '=', $request->tahun)
                            ->first(['id', 'target_tw1', 'target_tw2', 'target_tw3', 'target_tw4', 'tahun',
                                    'realisasi_tw1', 'realisasi_tw2', 'realisasi_tw3', 'realisasi_tw4',
                                'capaian_tw1', 'capaian_tw2', 'capaian_tw3', 'capaian_tw4', 'hambatan', 'tindak_lanjut'  ]);

                    $langkah = RencanaAksiLangkah::where('pohon_kinerja_indikator_id', '=', $ik->id)
                               ->where('tahun', '=',$request->tahun)
                               ->get();  
                    $langkah = $langkah->map(function($dl) use($request) 
                    {
                        return [
                            "id"                 => $dl->id,
                            "langkah"          => $dl->langkah,
                            "target_tw1"          => $dl->target_tw1,
                            "target_tw2"          => $dl->target_tw2,
                            "target_tw3"          => $dl->target_tw3,
                            "target_tw4"          => $dl->target_tw4,
                            "realisasi_tw1"          => $dl->realisasi_tw1,
                            "realisasi_tw2"          => $dl->realisasi_tw2,
                            "realisasi_tw3"          => $dl->realisasi_tw3,
                            "realisasi_tw4"          => $dl->realisasi_tw4,
                            "capaian_tw1"          => $dl->capaian_tw1,
                            "capaian_tw2"          => $dl->capaian_tw2,
                            "capaian_tw3"          => $dl->capaian_tw3,
                            "capaian_tw4"          => $dl->capaian_tw4
                        ];
                    });                

                    $target_pk = PerjanjianKinerja::where('pohon_kinerja_indikator_id', '=', $ik->id)
                    ->where('tahun', '=',$request->tahun)
                    ->get(['target', 'tahun', 'murni']); 

                    return $listindikator = [
                        "id"                 => $ik->id,
                        "indikator"          => $ik->indikator,
                        "rencana_aksi" => $rencana_aksi,
                        "langkah" => $langkah,
                        "target_perjanjian_kinerja" => $target_pk
                    ];
                });

                $list =  [
                    "id" => $item->id,
                    "pohon_kinerja_sasaran_id" => $item->pohon_kinerja_sasaran_id,
                    "sasaran" => $item->sasaran,
                    "order" => $item->order,
                    "anggaran_perjanjian_kinerja" => ['murni'=> $anggaran_murni, 'perubahan'=>$anggaran_perubahan],
                    "indikator" =>$indikator
                ];
                
                return $list;
            });

            return response()->json([
                'success' => true,
                'message' => 'List of Rencana Aksi Edit',
                'data' => $objData
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'List of Rencana Aksi Edit',
                'data' => [],
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function getTargetRpjmd($tahun, $indikator_id)
    {
        // Cek Target RPJMD
         $visi = BaseController::getCurrentVisi();             
         $tahun_awal = !empty($visi) ? $visi->period_starts : 0;             
         $tahun_akhir = !empty($visi) ? $visi->period_ends : 0;  

         $periode = array();
         for ($i = $tahun_awal; $i <= $tahun_akhir; $i++) {
            $periode[] = $i;
         }
        
         //if(in_array($tahun))
         return 199;
    }


    public function rpjmd()
    {
        $actions = [];
    
        $rawActions = PohonKinerjaVisi::with(
            ['misi' => 
                function($query) {
                     $query->where('is_active', true);                 
                     $query->with([
                        'tujuan' => 
                        function($query) {
                            $query->where('is_active', true);                 
                            $query->with([
                               'sasaran' => 
                               function($query) {
                                   $query->where('is_active', true);                 
                                   $query->with([
                                      'indikator_sasaran' => function($query){
                                        $query->with(['satuan']);
                                      }
                                   ]);
                               }                               
                            ]);
                        } 
                     ]);
                }            
        ])->where('is_active', '=', true)
        ->get()
        ->first();

       
        return response()->json([
            'success' => true,
            'message' => 'Visi found.',
            'actions' => $rawActions,
        ]);
    }
    

}

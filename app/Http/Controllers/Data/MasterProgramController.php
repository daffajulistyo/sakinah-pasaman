<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MasterProgramController extends Controller
{
    public function index()
    {
        return view('app.data.masterprogram._index');
    }

    public function tambah()
    {
        $opd = DB::table('master_opd')
            ->select('kode_opd', 'nama_opd')
            ->orderBy('nama_opd')
            ->get();
        return view('app.data.masterprogram._tambah', compact('opd'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_program' => 'required|string|max:50',
            'nama_program' => 'required|string|max:255',
            'kode_skpd'     => 'required|string|max:50',
            'tahun'         => 'required|digits:4',
        ]);

        DB::table('master_program')->insert([
            'id'            => Str::uuid(),
            'kode_program'  => $request->kode_program,
            'nama_program'  => $request->nama_program,
            'kode_skpd'     => $request->kode_skpd,
            'tahun'         => $request->tahun,
            'is_active'     => $request->boolean('is_active', true),
            'created_by'    => auth()->user()->username,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Master Program berhasil ditambahkan',
        ]);
    }

    public function destroy($id)
    {
        if (!Str::isUuid($id)) {
            return response()->json(['status' => false, 'message' => 'Invalid ID'], 422);
        }

        $program = DB::table('master_program')->where('id', $id)->first();
        if (!$program) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        DB::table('master_program')->where('id', $id)->delete();

        return response()->json([
            'status'  => true,
            'message' => "{$program->nama_program} berhasil dihapus",
        ]);
    }

    public function datatable(Request $request)
    {
        $searchColumn = collect(['kode_program', 'nama_program']);
        $currentPage  = $request->get('page', 1);
        $perPage      = $request->get('per_page', 10);
        $search       = $request->get('search', '');

        $query = DB::table('master_program')
            ->join('master_opd', 'master_opd.kode_opd', '=', 'master_program.kode_skpd')
            ->select(
                'master_program.id',
                'master_program.kode_program',
                'master_program.nama_program',
                'master_program.kode_skpd',
                'master_program.tahun',
                'master_program.is_active',
                'master_program.created_at',
                'master_opd.nama_opd'
            );

        if ($search != '') {
            $query->where(function ($q) use ($search, $searchColumn) {
                $searchColumn->each(function ($col, $i) use ($search, $q) {
                    if ($i == 0) $q->where($col, 'like', "%{$search}%");
                    else $q->orWhere($col, 'like', "%{$search}%");
                });
            });
        }

        $query->orderBy('master_program.created_at', 'desc');
        $objData     = $query->paginate($perPage);
        $totalPage   = $objData->lastPage();
        $totalRecord = $objData->total();

        $objData = $objData->map(function ($item) {
            $jam = Carbon::parse($item->created_at)->diffInHours();
            $created_at = $jam > 24
                ? Carbon::parse($item->created_at)->format('d M Y H:i')
                : Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();

            return [
                'id'           => $item->id,
                'kode_program' => $item->kode_program,
                'nama_program' => $item->nama_program,
                'kode_skpd'    => $item->kode_skpd,
                'nama_opd'     => $item->nama_opd,
                'tahun'        => $item->tahun,
                'is_active'    => $item->is_active,
                'created_at'   => $created_at,
            ];
        });

        return response()->json([
            'success'    => true,
            'message'    => 'List Master Program',
            'data'       => $objData,
            'pagination' => [
                'page'          => $currentPage,
                'per_page'      => $perPage,
                'total_records' => $totalRecord,
                'total_page'    => $totalPage,
            ],
        ]);
    }
}

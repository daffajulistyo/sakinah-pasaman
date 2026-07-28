<?php

namespace App\Http\Controllers\Api\v1\MASTER;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\MasterSubKegiatan;

class MasterSubKegiatanController extends Controller
{
    public function create(Request $request)
    {
        try {
            $form = $request->validate([
                "kode_sub_kegiatan"  => "required|string|max:50",
                "nama_sub_kegiatan"  => "required|string|max:255",
                "master_kegiatan_id" => "required|uuid|exists:master_kegiatan,id",
                "anggaran"            => "nullable|numeric",
                "is_active"           => "required|boolean"
            ]);

            $form['id'] = Str::uuid();
            $form['created_by'] = $request->attributes->get('payload')->username;
            $form['anggaran'] = $form['anggaran'] ?? 0;

            $data = MasterSubKegiatan::create($form);

            return response()->json([
                'success' => true,
                'message' => 'Successfully created new sub kegiatan.',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong!",
                "errors" => $th->getMessage()
            ], 500);
        }
    }

    public function read($id)
    {
        try {
            if (!Str::isUuid($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sub Kegiatan not Found',
                ], 422);
            }
            $subKegiatan = MasterSubKegiatan::with('kegiatan.program.opd')->find($id);
            if (!$subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub Kegiatan not found.',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Sub Kegiatan found.',
                'data' => $subKegiatan,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            if (!Str::isUuid($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sub Kegiatan not Found',
                ], 422);
            }
            $subKegiatan = MasterSubKegiatan::find($id);
            if (!$subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub Kegiatan not found.',
                ], 404);
            }

            $form = $request->validate([
                "kode_sub_kegiatan"  => "required|string|max:50",
                "nama_sub_kegiatan"  => "required|string|max:255",
                "master_kegiatan_id" => "required|uuid|exists:master_kegiatan,id",
                "anggaran"            => "nullable|numeric",
                "is_active"           => "required|boolean"
            ]);

            $form['anggaran'] = $form['anggaran'] ?? 0;
            $payload = $request->attributes->get('payload');
            $form['updated_by'] = $payload->username;

            $subKegiatan->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Sub Kegiatan updated successfully.',
                'data' => $subKegiatan,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            if (!Str::isUuid($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Id, Sub Kegiatan not Found',
                ], 422);
            }
            $subKegiatan = MasterSubKegiatan::find($id);
            if (!$subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub Kegiatan not found.',
                ], 404);
            }

            $subKegiatan->delete();
            return response()->json([
                'success' => true,
                'message' => 'Sub Kegiatan deleted successfully.',
                'data' => $subKegiatan,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function list(Request $request)
    {
        $searchColumn = collect(['kode_sub_kegiatan', 'nama_sub_kegiatan']);

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $is_active = $request->get('is_active', '');

        $query = MasterSubKegiatan::with('kegiatan.program.opd');

        if ($search != '') {
            $searchColumn->map(function ($item, $index) use ($search, $query) {
                if ($index == 0) $query->where($item, 'like', '%' . $search . '%');
                else $query->orWhere($item, 'like', '%' . $search . '%');
            });
        }

        if ($is_active != "") {
            $query->where('is_active', filter_var($is_active, FILTER_VALIDATE_BOOLEAN));
        }

        $query->orderBy('created_at', 'desc');
        $objData = $query->paginate($perPage);
        $totalPage = $objData->lastPage();
        $totalRecord = $objData->total();

        $objData = $objData->map(function ($item) {
            $jam = Carbon::parse($item->created_at)->diffInHours();
            if ($jam > 24) {
                $created_at = Carbon::parse($item->created_at)->format('d M Y H:i');
            } else {
                $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->diffForHumans();
            }
            return [
                "id"                 => $item->id,
                "kode_sub_kegiatan"  => $item->kode_sub_kegiatan,
                "nama_sub_kegiatan"  => $item->nama_sub_kegiatan,
                "master_kegiatan_id" => $item->master_kegiatan_id,
                "nama_kegiatan"      => $item->kegiatan->nama_kegiatan ?? null,
                "nama_program"       => $item->kegiatan->program->nama_program ?? null,
                "nama_opd"           => $item->kegiatan->program->opd->nama_opd ?? null,
                "anggaran"           => (float) $item->anggaran,
                "is_active"          => $item->is_active,
                "created_at"         => $created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Master Data Sub Kegiatan',
            'data' => $objData,
            'pagination' => [
                'page' => $currentPage,
                'per_page' => $perPage,
                'total_records' => $totalRecord,
                'total_page' => $totalPage,
                'search' => $search
            ]
        ]);
    }
}

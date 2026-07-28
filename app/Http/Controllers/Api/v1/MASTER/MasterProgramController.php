<?php

namespace App\Http\Controllers\Api\v1\MASTER;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\MasterProgram;
use App\Models\Sakip\MASTER\MasterOpd;

class MasterProgramController extends Controller
{
    public function create(Request $request)
    {
        try {
            $form = $request->validate([
                "kode_program"  => "required|string|max:50",
                "nama_program"  => "required|string|max:255",
                "master_opd_id" => "required|uuid|exists:master_opd,id",
                "tahun"         => "required|digits:4",
                "anggaran"      => "nullable|numeric",
                "is_active"     => "required|boolean"
            ]);

            $opd = MasterOpd::findOrFail($form['master_opd_id']);
            $form['id'] = Str::uuid();
            $form['kode_skpd'] = $opd->kode_opd;
            $form['created_by'] = $request->attributes->get('payload')->username;
            $form['anggaran'] = $form['anggaran'] ?? 0;

            $data = MasterProgram::create($form);

            return response()->json([
                'success' => true,
                'message' => 'Successfully created new program.',
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
                    'message' => 'Invalid Id, Program not Found',
                ], 422);
            }
            $program = MasterProgram::with('opd')->find($id);
            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found.',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Program found.',
                'data' => $program,
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
                    'message' => 'Invalid Id, Program not Found',
                ], 422);
            }
            $program = MasterProgram::find($id);
            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found.',
                ], 404);
            }

            $form = $request->validate([
                "kode_program"  => "required|string|max:50",
                "nama_program"  => "required|string|max:255",
                "master_opd_id" => "required|uuid|exists:master_opd,id",
                "tahun"         => "required|digits:4",
                "anggaran"      => "nullable|numeric",
                "is_active"     => "required|boolean"
            ]);

            $opd = MasterOpd::findOrFail($form['master_opd_id']);
            $form['kode_skpd'] = $opd->kode_opd;
            $form['anggaran'] = $form['anggaran'] ?? 0;
            $payload = $request->attributes->get('payload');
            $form['updated_by'] = $payload->username;

            $program->update($form);

            return response()->json([
                'success' => true,
                'message' => 'Program updated successfully.',
                'data' => $program,
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
                    'message' => 'Invalid Id, Program not Found',
                ], 422);
            }
            $program = MasterProgram::find($id);
            if (!$program) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program not found.',
                ], 404);
            }

            $program->delete();
            return response()->json([
                'success' => true,
                'message' => 'Program deleted successfully.',
                'data' => $program,
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
        $searchColumn = collect(['kode_program', 'nama_program']);

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $is_active = $request->get('is_active', '');

        $query = MasterProgram::with('opd');

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
                "id"            => $item->id,
                "kode_program"  => $item->kode_program,
                "nama_program"  => $item->nama_program,
                "kode_skpd"     => $item->kode_skpd,
                "master_opd_id" => $item->master_opd_id,
                "nama_opd"      => $item->opd->nama_opd ?? null,
                "tahun"         => $item->tahun,
                "anggaran"      => (float) $item->anggaran,
                "is_active"     => $item->is_active,
                "created_at"    => $created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of Master Data Program',
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

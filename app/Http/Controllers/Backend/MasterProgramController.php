<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterProgram;
use App\Models\Sakip\MASTER\MasterOpd;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterProgramController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = MasterProgram::with('opd')
            ->when($search, fn($q) => $q->where('kode_program', 'like', "%{$search}%")->orWhere('nama_program', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        $opds = MasterOpd::select('id', 'kode_opd', 'nama_opd')->orderBy('nama_opd')->get();
        return view('backend.program.index', compact('data', 'search', 'opds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_program' => 'required|string|max:50',
            'nama_program' => 'required|string|max:255',
            'master_opd_id' => 'required|uuid|exists:master_opd,id',
            'tahun' => 'required|digits:4',
        ]);
        $opd = MasterOpd::findOrFail($request->master_opd_id);
        MasterProgram::create([
            'id' => Str::uuid(),
            'kode_program' => $request->kode_program,
            'nama_program' => $request->nama_program,
            'kode_skpd' => $opd->kode_opd,
            'master_opd_id' => $request->master_opd_id,
            'tahun' => $request->tahun,
            'anggaran' => $request->anggaran ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->user()->username,
        ]);
        return redirect()->route('program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = MasterProgram::findOrFail($id);
        $request->validate([
            'kode_program' => 'required|string|max:50',
            'nama_program' => 'required|string|max:255',
            'master_opd_id' => 'required|uuid|exists:master_opd,id',
            'tahun' => 'required|digits:4',
        ]);
        $opd = MasterOpd::findOrFail($request->master_opd_id);
        $item->update([
            'kode_program' => $request->kode_program,
            'nama_program' => $request->nama_program,
            'kode_skpd' => $opd->kode_opd,
            'master_opd_id' => $request->master_opd_id,
            'tahun' => $request->tahun,
            'anggaran' => $request->anggaran ?? 0,
            'is_active' => $request->boolean('is_active', true),
            'updated_by' => auth()->user()->username,
        ]);
        return redirect()->route('program.index')->with('success', 'Program berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = MasterProgram::findOrFail($id);
        $item->delete();
        return redirect()->route('program.index')->with('success', $item->nama_program . ' berhasil dihapus.');
    }
}

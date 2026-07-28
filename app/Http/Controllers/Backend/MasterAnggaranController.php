<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterAnggaran;
use App\Models\MasterProgram;
use App\Models\Sakip\MASTER\MasterOpd;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterAnggaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = MasterAnggaran::with(['opd', 'program'])
            ->when($search, fn($q) => $q->where('kode_anggaran', 'like', "%{$search}%")->orWhere('nama_anggaran', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        return view('backend.anggaran.index', compact('data', 'search'));
    }

    public function create()
    {
        $opds = MasterOpd::select('id', 'kode_opd', 'nama_opd')->orderBy('nama_opd')->get();
        $programs = MasterProgram::select('id', 'kode_program', 'nama_program')->orderBy('nama_program')->get();
        return view('backend.anggaran.create', compact('opds', 'programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_anggaran' => 'required|string|max:50',
            'nama_anggaran' => 'required|string|max:255',
        ]);
        MasterAnggaran::create([
            'id' => Str::uuid(), 'kode_anggaran' => $request->kode_anggaran, 'nama_anggaran' => $request->nama_anggaran,
            'master_opd_id' => $request->master_opd_id, 'master_program_id' => $request->master_program_id,
            'jumlah' => $request->jumlah ?? 0, 'tahun' => $request->tahun, 'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->user()->username,
        ]);
        return redirect()->route('anggaran.index')->with('success', 'Anggaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = MasterAnggaran::findOrFail($id);
        $opds = MasterOpd::select('id', 'kode_opd', 'nama_opd')->orderBy('nama_opd')->get();
        $programs = MasterProgram::select('id', 'kode_program', 'nama_program')->orderBy('nama_program')->get();
        return view('backend.anggaran.edit', compact('item', 'opds', 'programs'));
    }

    public function update(Request $request, $id)
    {
        $item = MasterAnggaran::findOrFail($id);
        $request->validate([
            'kode_anggaran' => 'required|string|max:50',
            'nama_anggaran' => 'required|string|max:255',
        ]);
        $item->update([
            'kode_anggaran' => $request->kode_anggaran, 'nama_anggaran' => $request->nama_anggaran,
            'master_opd_id' => $request->master_opd_id, 'master_program_id' => $request->master_program_id,
            'jumlah' => $request->jumlah ?? 0, 'tahun' => $request->tahun, 'is_active' => $request->boolean('is_active', true),
            'updated_by' => auth()->user()->username,
        ]);
        return redirect()->route('anggaran.index')->with('success', 'Anggaran berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = MasterAnggaran::findOrFail($id);
        $item->delete();
        return redirect()->route('anggaran.index')->with('success', $item->nama_anggaran . ' berhasil dihapus.');
    }
}

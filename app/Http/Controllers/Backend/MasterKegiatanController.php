<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterKegiatan;
use App\Models\MasterProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = MasterKegiatan::with('program')
            ->when($search, fn($q) => $q->where('kode_kegiatan', 'like', "%{$search}%")->orWhere('nama_kegiatan', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        $programs = MasterProgram::select('id', 'kode_program', 'nama_program')->orderBy('kode_program')->get();
        return view('backend.kegiatan.index', compact('data', 'search', 'programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kegiatan' => 'required|string|max:50',
            'nama_kegiatan' => 'required|string|max:255',
            'master_program_id' => 'required|uuid|exists:master_program,id',
        ]);
        MasterKegiatan::create([
            'id' => Str::uuid(),
            'kode_kegiatan' => $request->kode_kegiatan,
            'nama_kegiatan' => $request->nama_kegiatan,
            'master_program_id' => $request->master_program_id,
            'anggaran' => $request->anggaran ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = MasterKegiatan::findOrFail($id);
        $request->validate([
            'kode_kegiatan' => 'required|string|max:50',
            'nama_kegiatan' => 'required|string|max:255',
            'master_program_id' => 'required|uuid|exists:master_program,id',
        ]);
        $item->update([
            'kode_kegiatan' => $request->kode_kegiatan,
            'nama_kegiatan' => $request->nama_kegiatan,
            'master_program_id' => $request->master_program_id,
            'anggaran' => $request->anggaran ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = MasterKegiatan::findOrFail($id);
        $item->delete();
        return redirect()->route('kegiatan.index')->with('success', $item->nama_kegiatan . ' berhasil dihapus.');
    }
}

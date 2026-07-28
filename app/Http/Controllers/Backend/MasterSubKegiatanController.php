<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterSubKegiatan;
use App\Models\MasterKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterSubKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = MasterSubKegiatan::with('kegiatan.program')
            ->when($search, fn($q) => $q->where('kode_sub_kegiatan', 'like', "%{$search}%")->orWhere('nama_sub_kegiatan', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        $kegiatans = MasterKegiatan::with('program')->orderBy('kode_kegiatan')->get();
        return view('backend.sub-kegiatan.index', compact('data', 'search', 'kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_sub_kegiatan' => 'required|string|max:50',
            'nama_sub_kegiatan' => 'required|string|max:255',
            'master_kegiatan_id' => 'required|uuid|exists:master_kegiatan,id',
        ]);
        MasterSubKegiatan::create([
            'id' => Str::uuid(),
            'kode_sub_kegiatan' => $request->kode_sub_kegiatan,
            'nama_sub_kegiatan' => $request->nama_sub_kegiatan,
            'master_kegiatan_id' => $request->master_kegiatan_id,
            'anggaran' => $request->anggaran ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);
        return redirect()->route('sub-kegiatan.index')->with('success', 'Sub Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = MasterSubKegiatan::findOrFail($id);
        $request->validate([
            'kode_sub_kegiatan' => 'required|string|max:50',
            'nama_sub_kegiatan' => 'required|string|max:255',
            'master_kegiatan_id' => 'required|uuid|exists:master_kegiatan,id',
        ]);
        $item->update([
            'kode_sub_kegiatan' => $request->kode_sub_kegiatan,
            'nama_sub_kegiatan' => $request->nama_sub_kegiatan,
            'master_kegiatan_id' => $request->master_kegiatan_id,
            'anggaran' => $request->anggaran ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);
        return redirect()->route('sub-kegiatan.index')->with('success', 'Sub Kegiatan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = MasterSubKegiatan::findOrFail($id);
        $item->delete();
        return redirect()->route('sub-kegiatan.index')->with('success', $item->nama_sub_kegiatan . ' berhasil dihapus.');
    }
}

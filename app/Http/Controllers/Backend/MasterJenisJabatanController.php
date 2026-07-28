<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RefJenisJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterJenisJabatanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = RefJenisJabatan::when($search, fn($q) => $q->where('kode', 'like', "%{$search}%")->orWhere('nama', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        return view('backend.jenis-jabatan.index', compact('data', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:master_jenis_jabatan,kode',
            'nama' => 'required|string|max:200',
        ]);
        RefJenisJabatan::create(['id' => Str::uuid(), 'kode' => $request->kode, 'nama' => $request->nama, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('jenis-jabatan.index')->with('success', 'Jenis Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = RefJenisJabatan::findOrFail($id);
        $request->validate([
            'kode' => 'required|string|max:50|unique:master_jenis_jabatan,kode,' . $id,
            'nama' => 'required|string|max:200',
        ]);
        $item->update(['kode' => $request->kode, 'nama' => $request->nama, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('jenis-jabatan.index')->with('success', 'Jenis Jabatan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = RefJenisJabatan::findOrFail($id);
        $item->delete();
        return redirect()->route('jenis-jabatan.index')->with('success', $item->nama . ' berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RefJabatan;
use App\Models\RefJenisJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterJabatanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = RefJabatan::with('jenisJabatan')
            ->when($search, fn($q) => $q->where('kode', 'like', "%{$search}%")->orWhere('nama', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        $jenis = RefJenisJabatan::where('is_active', true)->orderBy('nama')->get(['id', 'nama']);
        return view('backend.jabatan.index', compact('data', 'search', 'jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:master_jabatan,kode',
            'nama' => 'required|string|max:200',
            'ref_jenis_jabatan_id' => 'required|uuid|exists:master_jenis_jabatan,id',
        ]);
        RefJabatan::create(['id' => Str::uuid(), 'kode' => $request->kode, 'nama' => $request->nama, 'ref_jenis_jabatan_id' => $request->ref_jenis_jabatan_id, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = RefJabatan::findOrFail($id);
        $request->validate([
            'kode' => 'required|string|max:50|unique:master_jabatan,kode,' . $id,
            'nama' => 'required|string|max:200',
            'ref_jenis_jabatan_id' => 'required|uuid|exists:master_jenis_jabatan,id',
        ]);
        $item->update(['kode' => $request->kode, 'nama' => $request->nama, 'ref_jenis_jabatan_id' => $request->ref_jenis_jabatan_id, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = RefJabatan::findOrFail($id);
        $item->delete();
        return redirect()->route('jabatan.index')->with('success', $item->nama . ' berhasil dihapus.');
    }
}

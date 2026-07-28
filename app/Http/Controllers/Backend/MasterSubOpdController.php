<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RefSubOpd;
use App\Models\Sakip\MASTER\MasterOpd;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterSubOpdController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = RefSubOpd::with('opd')
            ->when($search, fn($q) => $q->where('kode', 'like', "%{$search}%")->orWhere('nama', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        $opds = MasterOpd::select('id', 'kode_opd', 'nama_opd')->orderBy('nama_opd')->get();
        return view('backend.sub-opd.index', compact('data', 'search', 'opds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:100|unique:master_sub_opd,kode',
            'nama' => 'required|string|max:200',
            'master_opd_id' => 'nullable|uuid|exists:master_opd,id',
        ]);
        RefSubOpd::create(['id' => Str::uuid(), 'kode' => $request->kode, 'nama' => $request->nama, 'master_opd_id' => $request->master_opd_id, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('sub-opd.index')->with('success', 'Sub OPD berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = RefSubOpd::findOrFail($id);
        $request->validate([
            'kode' => 'required|string|max:100|unique:master_sub_opd,kode,' . $id,
            'nama' => 'required|string|max:200',
            'master_opd_id' => 'nullable|uuid|exists:master_opd,id',
        ]);
        $item->update(['kode' => $request->kode, 'nama' => $request->nama, 'master_opd_id' => $request->master_opd_id, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('sub-opd.index')->with('success', 'Sub OPD berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = RefSubOpd::findOrFail($id);
        $item->delete();
        return redirect()->route('sub-opd.index')->with('success', $item->nama . ' berhasil dihapus.');
    }
}

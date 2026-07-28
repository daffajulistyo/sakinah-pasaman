<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sakip\MASTER\MasterOpd;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterOpdController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = MasterOpd::when($search, fn($q) => $q->where('kode_opd', 'like', "%{$search}%")->orWhere('nama_opd', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        return view('backend.opd.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('backend.opd.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_opd' => 'required|string|max:255|unique:master_opd,kode_opd',
            'nama_opd' => 'required|string|max:255',
        ]);
        MasterOpd::create([
            'id' => Str::uuid(), 'kode_opd' => $request->kode_opd, 'nama_opd' => $request->nama_opd,
            'alamat' => $request->alamat, 'telp' => $request->telp, 'email' => $request->email,
            'website' => $request->website, 'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->user()->username,
        ]);
        return redirect()->route('opd.index')->with('success', 'OPD berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = MasterOpd::findOrFail($id);
        return view('backend.opd.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = MasterOpd::findOrFail($id);
        $request->validate([
            'kode_opd' => 'required|string|max:255|unique:master_opd,kode_opd,' . $id,
            'nama_opd' => 'required|string|max:255',
        ]);
        $item->update([
            'kode_opd' => $request->kode_opd, 'nama_opd' => $request->nama_opd,
            'alamat' => $request->alamat, 'telp' => $request->telp, 'email' => $request->email,
            'website' => $request->website, 'is_active' => $request->boolean('is_active', true),
            'updated_by' => auth()->user()->username,
        ]);
        return redirect()->route('opd.index')->with('success', 'OPD berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = MasterOpd::findOrFail($id);
        $item->delete();
        return redirect()->route('opd.index')->with('success', $item->nama_opd . ' berhasil dihapus.');
    }
}

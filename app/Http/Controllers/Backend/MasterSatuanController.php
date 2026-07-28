<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sakip\MASTER\MasterSatuan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterSatuanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = MasterSatuan::when($search, fn($q) => $q->where('satuan', 'like', "%{$search}%")->orWhere('keterangan', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        return view('backend.satuan.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('backend.satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'satuan' => 'required|string|max:100',
        ]);
        MasterSatuan::create([
            'id' => Str::uuid(), 'satuan' => $request->satuan, 'keterangan' => $request->keterangan,
            'is_active' => $request->boolean('is_active', false), 'created_by' => auth()->user()->username,
        ]);
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = MasterSatuan::findOrFail($id);
        return view('backend.satuan.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = MasterSatuan::findOrFail($id);
        $request->validate(['satuan' => 'required|string|max:100']);
        $item->update([
            'satuan' => $request->satuan, 'keterangan' => $request->keterangan,
            'is_active' => $request->boolean('is_active', false), 'updated_by' => auth()->user()->username,
        ]);
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = MasterSatuan::findOrFail($id);
        $item->delete();
        return redirect()->route('satuan.index')->with('success', $item->satuan . ' berhasil dihapus.');
    }
}

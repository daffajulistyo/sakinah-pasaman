<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RefEselon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterEselonController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = RefEselon::when($search, fn($q) => $q->where('kode', 'like', "%{$search}%")->orWhere('nama', 'like', "%{$search}%"))
            ->orderBy('level')->orderBy('created_at', 'desc')
            ->paginate(10)->appends(['search' => $search]);
        return view('backend.eselon.index', compact('data', 'search'));
    }

    public function create()
    {
        return view('backend.eselon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:master_eselon,kode',
            'nama' => 'required|string|max:100',
            'level' => 'required|integer|min:0',
        ]);
        RefEselon::create(['id' => Str::uuid(), 'kode' => $request->kode, 'nama' => $request->nama, 'level' => $request->level, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('eselon.index')->with('success', 'Eselon berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = RefEselon::findOrFail($id);
        return view('backend.eselon.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = RefEselon::findOrFail($id);
        $request->validate([
            'kode' => 'required|string|max:50|unique:master_eselon,kode,' . $id,
            'nama' => 'required|string|max:100',
            'level' => 'required|integer|min:0',
        ]);
        $item->update(['kode' => $request->kode, 'nama' => $request->nama, 'level' => $request->level, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('eselon.index')->with('success', 'Eselon berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = RefEselon::findOrFail($id);
        $item->delete();
        return redirect()->route('eselon.index')->with('success', $item->nama . ' berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RefGolongan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterGolonganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = RefGolongan::when($search, fn($q) => $q->where('kode', 'like', "%{$search}%")->orWhere('golongan', 'like', "%{$search}%")->orWhere('pangkat', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        return view('backend.golongan.index', compact('data', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:master_golongan,kode',
            'golongan' => 'required|string|max:50',
            'pangkat' => 'required|string|max:200',
        ]);
        RefGolongan::create(['id' => Str::uuid(), 'kode' => $request->kode, 'golongan' => $request->golongan, 'pangkat' => $request->pangkat, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = RefGolongan::findOrFail($id);
        $request->validate([
            'kode' => 'required|string|max:50|unique:master_golongan,kode,' . $id,
            'golongan' => 'required|string|max:50',
            'pangkat' => 'required|string|max:200',
        ]);
        $item->update(['kode' => $request->kode, 'golongan' => $request->golongan, 'pangkat' => $request->pangkat, 'is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = RefGolongan::findOrFail($id);
        $item->delete();
        return redirect()->route('golongan.index')->with('success', $item->golongan . ' berhasil dihapus.');
    }
}

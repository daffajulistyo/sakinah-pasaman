<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MasterProgramController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = DB::table('master_program')
            ->leftJoin('master_opd', 'master_opd.kode_opd', '=', 'master_program.kode_skpd')
            ->select('master_program.*', 'master_opd.nama_opd')
            ->when($search, fn($q) => $q->where('master_program.kode_program', 'like', "%{$search}%")->orWhere('master_program.nama_program', 'like', "%{$search}%"))
            ->orderBy('master_program.created_at', 'desc')
            ->paginate(10)->appends(['search' => $search]);
        return view('backend.program.index', compact('data', 'search'));
    }

    public function create()
    {
        $opds = DB::table('master_opd')->select('kode_opd', 'nama_opd')->orderBy('nama_opd')->get();
        return view('backend.program.create', compact('opds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_program' => 'required|string|max:50',
            'nama_program' => 'required|string|max:255',
            'kode_skpd' => 'required|string|max:50',
            'tahun' => 'required|digits:4',
        ]);
        DB::table('master_program')->insert([
            'id' => Str::uuid(), 'kode_program' => $request->kode_program, 'nama_program' => $request->nama_program,
            'kode_skpd' => $request->kode_skpd, 'tahun' => $request->tahun, 'is_active' => $request->boolean('is_active', true),
            'created_by' => auth()->user()->username, 'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = DB::table('master_program')->where('id', $id)->firstOrFail();
        $opds = DB::table('master_opd')->select('kode_opd', 'nama_opd')->orderBy('nama_opd')->get();
        return view('backend.program.edit', compact('item', 'opds'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_program' => 'required|string|max:50',
            'nama_program' => 'required|string|max:255',
            'kode_skpd' => 'required|string|max:50',
            'tahun' => 'required|digits:4',
        ]);
        DB::table('master_program')->where('id', $id)->update([
            'kode_program' => $request->kode_program, 'nama_program' => $request->nama_program,
            'kode_skpd' => $request->kode_skpd, 'tahun' => $request->tahun, 'is_active' => $request->boolean('is_active', true),
            'updated_by' => auth()->user()->username, 'updated_at' => now(),
        ]);
        return redirect()->route('program.index')->with('success', 'Program berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = DB::table('master_program')->where('id', $id)->first();
        if ($item) DB::table('master_program')->where('id', $id)->delete();
        return redirect()->route('program.index')->with('success', ($item->nama_program ?? '') . ' berhasil dihapus.');
    }
}

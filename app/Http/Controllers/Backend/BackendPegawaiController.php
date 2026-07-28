<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Sakip\MASTER\MasterOpd;
use App\Models\RefEselon;
use App\Models\RefGolongan;
use App\Models\RefJenisJabatan;
use App\Models\RefJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BackendPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = Pegawai::with(['opd', 'refEselon', 'refGolongan', 'refJenisJabatan', 'refJabatan'])
            ->when($search, fn($q) => $q->where('nip', 'like', "%{$search}%")->orWhere('nama', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);
        return view('backend.pegawai.index', compact('data', 'search'));
    }

    public function create()
    {
        $opds = MasterOpd::select('id', 'nama_opd')->orderBy('nama_opd')->get();
        $eselons = RefEselon::where('is_active', true)->orderBy('level')->get();
        $golongans = RefGolongan::where('is_active', true)->orderBy('kode')->get();
        $jenisJabatans = RefJenisJabatan::where('is_active', true)->orderBy('nama')->get();
        $jabatans = RefJabatan::where('is_active', true)->orderBy('nama')->get();
        return view('backend.pegawai.create', compact('opds', 'eselons', 'golongans', 'jenisJabatans', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|max:50|unique:pegawai,nip',
            'nama' => 'required|string|max:200',
        ]);
        Pegawai::create([
            'id' => Str::uuid(), 'nip' => $request->nip, 'nama' => $request->nama,
            'gelar_depan' => $request->gelar_depan, 'gelar_belakang' => $request->gelar_belakang,
            'tempat_lahir' => $request->tempat_lahir, 'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin, 'alamat' => $request->alamat,
            'no_hp' => $request->no_hp, 'email' => $request->email,
            'master_opd_id' => $request->master_opd_id, 'ref_eselon_id' => $request->ref_eselon_id,
            'ref_golongan_id' => $request->ref_golongan_id, 'ref_jenis_jabatan_id' => $request->ref_jenis_jabatan_id,
            'ref_jabatan_id' => $request->ref_jabatan_id, 'jenjang' => $request->jenjang,
            'is_active' => $request->boolean('is_active', true), 'created_by' => auth()->user()->username,
        ]);
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = Pegawai::findOrFail($id);
        $opds = MasterOpd::select('id', 'nama_opd')->orderBy('nama_opd')->get();
        $eselons = RefEselon::where('is_active', true)->orderBy('level')->get();
        $golongans = RefGolongan::where('is_active', true)->orderBy('kode')->get();
        $jenisJabatans = RefJenisJabatan::where('is_active', true)->orderBy('nama')->get();
        $jabatans = RefJabatan::where('is_active', true)->orderBy('nama')->get();
        return view('backend.pegawai.edit', compact('item', 'opds', 'eselons', 'golongans', 'jenisJabatans', 'jabatans'));
    }

    public function update(Request $request, $id)
    {
        $item = Pegawai::findOrFail($id);
        $request->validate([
            'nip' => 'required|string|max:50|unique:pegawai,nip,' . $id,
            'nama' => 'required|string|max:200',
        ]);
        $item->update([
            'nip' => $request->nip, 'nama' => $request->nama,
            'gelar_depan' => $request->gelar_depan, 'gelar_belakang' => $request->gelar_belakang,
            'tempat_lahir' => $request->tempat_lahir, 'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin, 'alamat' => $request->alamat,
            'no_hp' => $request->no_hp, 'email' => $request->email,
            'master_opd_id' => $request->master_opd_id, 'ref_eselon_id' => $request->ref_eselon_id,
            'ref_golongan_id' => $request->ref_golongan_id, 'ref_jenis_jabatan_id' => $request->ref_jenis_jabatan_id,
            'ref_jabatan_id' => $request->ref_jabatan_id, 'jenjang' => $request->jenjang,
            'is_active' => $request->boolean('is_active', true), 'updated_by' => auth()->user()->username,
        ]);
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diupdate.');
    }

    public function destroy($id)
    {
        $item = Pegawai::findOrFail($id);
        $item->delete();
        return redirect()->route('pegawai.index')->with('success', $item->nama . ' berhasil dihapus.');
    }
}

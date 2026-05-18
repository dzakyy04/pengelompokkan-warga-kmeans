<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\MasterPekerjaan;
use App\Models\MasterKondisiRumah;
use App\Models\MasterAset;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $query = Warga::with(['pekerjaan', 'kondisiRumah', 'asets', 'latestClusteringResult']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama_lengkap', 'like', "%{$s}%")->orWhere('nik', 'like', "%{$s}%"));
        }
        if ($request->filled('kelompok')) {
            $query->whereHas('latestClusteringResult', function($q) use ($request) {
                $q->where('label', $request->kelompok);
            });
        }

        $wargas = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        $pekerjaans = MasterPekerjaan::orderBy('nama')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $asets = MasterAset::orderBy('nama')->get();

        return view('admin.warga.index', compact('wargas', 'pekerjaans', 'kondisiRumahs', 'asets'));
    }

    public function create()
    {
        $pekerjaans = MasterPekerjaan::orderBy('nama')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $asets = MasterAset::orderBy('nama')->get();
        return view('admin.warga.create', compact('pekerjaans', 'kondisiRumahs', 'asets'));
    }

    public function store(Request $request)
    {
        if ($request->has('pendapatan')) {
            $request->merge(['pendapatan' => str_replace('.', '', $request->pendapatan)]);
        }
        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik',
            'pekerjaan_id' => 'required',
            'pekerjaan_baru' => 'required_if:pekerjaan_id,lainnya|max:255',
            'pendapatan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumah,id',
        ]);

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id');
        $data['pendapatan'] = (int) str_replace('.', '', $data['pendapatan']);

        if ($request->pekerjaan_id === 'lainnya') {
            $pekerjaan = MasterPekerjaan::firstOrCreate(['nama' => $request->pekerjaan_baru]);
            $data['pekerjaan_id'] = $pekerjaan->id;
        } else {
            $request->validate(['pekerjaan_id' => 'exists:master_pekerjaan,id']);
            $data['pekerjaan_id'] = $request->pekerjaan_id;
        }

        $warga = Warga::create($data);
        if ($request->has('asets')) {
            $warga->asets()->sync($request->asets);
        }

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function edit(Warga $warga)
    {
        $warga->load('asets');
        $pekerjaans = MasterPekerjaan::orderBy('nama')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $asets = MasterAset::orderBy('nama')->get();
        return view('admin.warga.edit', compact('warga', 'pekerjaans', 'kondisiRumahs', 'asets'));
    }

    public function update(Request $request, Warga $warga)
    {
        if ($request->has('pendapatan')) {
            $request->merge(['pendapatan' => str_replace('.', '', $request->pendapatan)]);
        }
        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik,' . $warga->id,
            'pekerjaan_id' => 'required',
            'pekerjaan_baru' => 'required_if:pekerjaan_id,lainnya|max:255',
            'pendapatan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumah,id',
        ]);

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id');
        $data['pendapatan'] = (int) str_replace('.', '', $data['pendapatan']);

        if ($request->pekerjaan_id === 'lainnya') {
            $pekerjaan = MasterPekerjaan::firstOrCreate(['nama' => $request->pekerjaan_baru]);
            $data['pekerjaan_id'] = $pekerjaan->id;
        } else {
            $request->validate(['pekerjaan_id' => 'exists:master_pekerjaan,id']);
            $data['pekerjaan_id'] = $request->pekerjaan_id;
        }

        $warga->update($data);
        $warga->asets()->sync($request->asets ?? []);

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(Warga $warga)
    {
        $warga->asets()->detach();
        $warga->delete();
        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil dihapus.');
    }
}

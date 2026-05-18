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
        if ($request->filled('pekerjaan_id')) {
            $query->where('pekerjaan_id', $request->pekerjaan_id);
        }

        $wargas = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();
        $pekerjaans = MasterPekerjaan::orderBy('nama')->get();

        return view('admin.warga.index', compact('wargas', 'pekerjaans'));
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
        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik',
            'pekerjaan_id' => 'required|exists:master_pekerjaans,id',
            'pendapatan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumahs,id',
        ]);

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pekerjaan_id', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id');
        $data['pendapatan'] = (int) str_replace('.', '', $data['pendapatan']);

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
        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik,' . $warga->id,
            'pekerjaan_id' => 'required|exists:master_pekerjaans,id',
            'pendapatan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumahs,id',
        ]);

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pekerjaan_id', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id');
        $data['pendapatan'] = (int) str_replace('.', '', $data['pendapatan']);

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

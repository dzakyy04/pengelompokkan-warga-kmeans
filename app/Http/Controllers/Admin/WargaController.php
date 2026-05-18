<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\MasterPendidikan;
use App\Models\MasterKondisiRumah;
use App\Models\MasterBansos;
use Illuminate\Http\Request;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $query = Warga::with(['pendidikan', 'kondisiRumah', 'bansos', 'latestClusteringResult']);

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

        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('nama')->get();

        return view('admin.warga.index', compact('wargas', 'pendidikans', 'kondisiRumahs', 'bansos'));
    }

    public function create()
    {
        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('nama')->get();
        return view('admin.warga.create', compact('pendidikans', 'kondisiRumahs', 'bansos'));
    }

    public function store(Request $request)
    {
        if ($request->has('pendapatan')) {
            $request->merge(['pendapatan' => str_replace('.', '', $request->pendapatan)]);
        }
        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik',
            'pendidikan_id' => 'required|exists:master_pendidikan,id',
            'pendapatan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumah,id',
            'bansos' => 'nullable|array',
            'bansos.*' => 'exists:master_bansos,id',
        ]);

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pendidikan_id', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id');
        $data['pendapatan'] = (int) str_replace('.', '', $data['pendapatan']);

        $warga = Warga::create($data);
        if ($request->has('bansos')) {
            $warga->bansos()->sync($request->bansos);
        }

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil ditambahkan.');
    }

    public function edit(Warga $warga)
    {
        $warga->load('bansos');
        $pendidikans = MasterPendidikan::orderBy('skor')->get();
        $kondisiRumahs = MasterKondisiRumah::orderBy('nama')->get();
        $bansos = MasterBansos::orderBy('nama')->get();
        return view('admin.warga.edit', compact('warga', 'pendidikans', 'kondisiRumahs', 'bansos'));
    }

    public function update(Request $request, Warga $warga)
    {
        if ($request->has('pendapatan')) {
            $request->merge(['pendapatan' => str_replace('.', '', $request->pendapatan)]);
        }
        $request->validate([
            'nama_lengkap' => 'required|max:255',
            'nik' => 'required|min:16|max:16|unique:wargas,nik,' . $warga->id,
            'pendidikan_id' => 'required|exists:master_pendidikan,id',
            'pendapatan' => 'required|numeric|min:0',
            'jumlah_tanggungan' => 'required|integer|min:0|max:20',
            'kondisi_rumah_id' => 'required|exists:master_kondisi_rumah,id',
            'bansos' => 'nullable|array',
            'bansos.*' => 'exists:master_bansos,id',
        ]);

        $data = $request->only('nama_lengkap', 'nik', 'rt_rw', 'pendidikan_id', 'pendapatan', 'jumlah_tanggungan', 'kondisi_rumah_id');
        $data['pendapatan'] = (int) str_replace('.', '', $data['pendapatan']);

        $warga->update($data);
        $warga->bansos()->sync($request->bansos ?? []);

        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function destroy(Warga $warga)
    {
        $warga->bansos()->detach();
        $warga->delete();
        return redirect()->route('admin.warga.index')->with('success', 'Data warga berhasil dihapus.');
    }
}

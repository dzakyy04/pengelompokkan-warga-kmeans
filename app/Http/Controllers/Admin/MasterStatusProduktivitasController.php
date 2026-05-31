<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPekerjaan;
use App\Models\Warga;
use Illuminate\Http\Request;

class MasterStatusProduktivitasController extends Controller
{
    public function index()
    {
        $pekerjaans = MasterPekerjaan::orderBy('skor')->orderBy('nama')->get();

        // Hitung jumlah warga per status_produktivitas
        $counts = Warga::selectRaw('status_produktivitas, count(*) as total')
            ->whereNotNull('status_produktivitas')
            ->groupBy('status_produktivitas')
            ->pluck('total', 'status_produktivitas');

        $statusList = MasterPekerjaan::statusProduktivitasList();

        return view('admin.master.status-produktivitas.index', compact('pekerjaans', 'counts', 'statusList'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $request->validate([
            'nama' => 'required|string|max:255|unique:master_pekerjaan,nama',
            'status_produktivitas' => 'required|string|in:Tidak Produktif,Tidak Stabil,Cukup Stabil,Stabil',
            'skor' => 'required|integer|min:1|max:4',
        ]);

        MasterPekerjaan::create($request->only('nama', 'status_produktivitas', 'skor'));

        return back()->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $pekerjaan = MasterPekerjaan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:master_pekerjaan,nama,' . $pekerjaan->id,
            'status_produktivitas' => 'required|string|in:Tidak Produktif,Tidak Stabil,Cukup Stabil,Stabil',
            'skor' => 'required|integer|min:1|max:4',
        ]);

        $oldNama = $pekerjaan->nama;
        $pekerjaan->update($request->only('nama', 'status_produktivitas', 'skor'));

        // Update warga yang menggunakan pekerjaan ini
        if ($oldNama !== $request->nama || $pekerjaan->wasChanged(['status_produktivitas', 'skor'])) {
            Warga::where('pekerjaan', $oldNama)->update([
                'pekerjaan' => $request->nama,
                'status_produktivitas' => $request->status_produktivitas,
                'skor_produktivitas' => $request->skor,
            ]);
        }

        return back()->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $pekerjaan = MasterPekerjaan::findOrFail($id);

        // Cek apakah ada warga yang menggunakan pekerjaan ini
        $wargaCount = Warga::where('pekerjaan', $pekerjaan->nama)->count();
        if ($wargaCount > 0) {
            return back()->with('error', "Tidak dapat menghapus. Masih ada {$wargaCount} warga dengan pekerjaan \"{$pekerjaan->nama}\".");
        }

        $pekerjaan->delete();

        return back()->with('success', 'Pekerjaan berhasil dihapus.');
    }
}

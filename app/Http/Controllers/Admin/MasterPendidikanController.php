<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPendidikan;
use Illuminate\Http\Request;

class MasterPendidikanController extends Controller
{
    public function index()
    {
        $items = MasterPendidikan::withCount('wargas')->orderBy('skor')->get();
        return view('admin.master.pendidikan.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|max:255', 'skor' => 'required|integer|min:1|max:10']);
        MasterPendidikan::create($request->only('nama', 'skor'));
        return redirect()->route('admin.master-pendidikan.index')->with('success', 'Pendidikan berhasil ditambahkan.');
    }

    public function update(Request $request, MasterPendidikan $masterPendidikan)
    {
        $request->validate(['nama' => 'required|max:255', 'skor' => 'required|integer|min:1|max:10']);
        $masterPendidikan->update($request->only('nama', 'skor'));
        return redirect()->route('admin.master-pendidikan.index')->with('success', 'Pendidikan berhasil diperbarui.');
    }

    public function destroy(MasterPendidikan $masterPendidikan)
    {
        if ($masterPendidikan->wargas()->count() > 0) {
            return back()->with('error', 'Tidak bisa dihapus, masih digunakan oleh data warga.');
        }
        $masterPendidikan->delete();
        return redirect()->route('admin.master-pendidikan.index')->with('success', 'Pendidikan berhasil dihapus.');
    }
}

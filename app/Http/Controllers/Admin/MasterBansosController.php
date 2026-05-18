<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterBansos;
use Illuminate\Http\Request;

class MasterBansosController extends Controller
{
    public function index()
    {
        $items = MasterBansos::withCount('wargas')->orderBy('nama')->paginate(15);
        return view('admin.master.bansos.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|max:255', 'keterangan' => 'nullable|max:255']);
        MasterBansos::create($request->only('nama', 'keterangan'));
        return redirect()->route('admin.master-bansos.index')->with('success', 'Bansos berhasil ditambahkan.');
    }

    public function update(Request $request, MasterBansos $masterBansos)
    {
        $request->validate(['nama' => 'required|max:255', 'keterangan' => 'nullable|max:255']);
        $masterBansos->update($request->only('nama', 'keterangan'));
        return redirect()->route('admin.master-bansos.index')->with('success', 'Bansos berhasil diperbarui.');
    }

    public function destroy(MasterBansos $masterBansos)
    {
        if ($masterBansos->wargas()->count() > 0) {
            return back()->with('error', 'Tidak bisa dihapus, masih digunakan oleh data warga.');
        }
        $masterBansos->delete();
        return redirect()->route('admin.master-bansos.index')->with('success', 'Bansos berhasil dihapus.');
    }
}

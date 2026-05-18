<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPekerjaan;
use Illuminate\Http\Request;

class MasterPekerjaanController extends Controller
{
    public function index()
    {
        $items = MasterPekerjaan::withCount('wargas')->orderBy('nama')->paginate(15);
        return view('admin.master.pekerjaan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.master.pekerjaan.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|max:255|unique:master_pekerjaans,nama']);
        MasterPekerjaan::create($request->only('nama'));
        return redirect()->route('admin.master-pekerjaan.index')->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function edit(MasterPekerjaan $masterPekerjaan)
    {
        return view('admin.master.pekerjaan.edit', ['item' => $masterPekerjaan]);
    }

    public function update(Request $request, MasterPekerjaan $masterPekerjaan)
    {
        $request->validate(['nama' => 'required|max:255|unique:master_pekerjaans,nama,' . $masterPekerjaan->id]);
        $masterPekerjaan->update($request->only('nama'));
        return redirect()->route('admin.master-pekerjaan.index')->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(MasterPekerjaan $masterPekerjaan)
    {
        if ($masterPekerjaan->wargas()->count() > 0) {
            return back()->with('error', 'Tidak bisa dihapus, masih digunakan oleh data warga.');
        }
        $masterPekerjaan->delete();
        return redirect()->route('admin.master-pekerjaan.index')->with('success', 'Pekerjaan berhasil dihapus.');
    }
}

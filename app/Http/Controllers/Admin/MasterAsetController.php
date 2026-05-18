<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAset;
use Illuminate\Http\Request;

class MasterAsetController extends Controller
{
    public function index()
    {
        $items = MasterAset::withCount('wargas')->orderBy('nama')->paginate(15);
        return view('admin.master.aset.index', compact('items'));
    }

    public function create()
    {
        return view('admin.master.aset.create');
    }

    public function store(Request $request)
    {
        if ($request->has('estimasi_nilai')) {
            $request->merge(['estimasi_nilai' => str_replace('.', '', $request->estimasi_nilai)]);
        }
        $request->validate(['nama' => 'required|max:255', 'estimasi_nilai' => 'required|numeric|min:0']);
        $data = $request->only('nama', 'estimasi_nilai');
        MasterAset::create($data);
        return redirect()->route('admin.master-aset.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(MasterAset $masterAset)
    {
        return view('admin.master.aset.edit', ['item' => $masterAset]);
    }

    public function update(Request $request, MasterAset $masterAset)
    {
        if ($request->has('estimasi_nilai')) {
            $request->merge(['estimasi_nilai' => str_replace('.', '', $request->estimasi_nilai)]);
        }
        $request->validate(['nama' => 'required|max:255', 'estimasi_nilai' => 'required|numeric|min:0']);
        $data = $request->only('nama', 'estimasi_nilai');
        $masterAset->update($data);
        return redirect()->route('admin.master-aset.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(MasterAset $masterAset)
    {
        if ($masterAset->wargas()->count() > 0) {
            return back()->with('error', 'Tidak bisa dihapus, masih digunakan oleh data warga.');
        }
        $masterAset->delete();
        return redirect()->route('admin.master-aset.index')->with('success', 'Aset berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterKondisiRumah;
use Illuminate\Http\Request;

class MasterKondisiRumahController extends Controller
{
    public function index()
    {
        $items = MasterKondisiRumah::withCount('wargas')->orderBy('skor')->get();
        return view('admin.master.kondisi-rumah.index', compact('items'));
    }

    public function create()
    {
        return view('admin.master.kondisi-rumah.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|max:255', 'skor' => 'required|integer|min:1|max:3']);
        MasterKondisiRumah::create($request->only('nama', 'skor'));
        return redirect()->route('admin.master-kondisi-rumah.index')->with('success', 'Kondisi rumah berhasil ditambahkan.');
    }

    public function edit(MasterKondisiRumah $masterKondisiRumah)
    {
        return view('admin.master.kondisi-rumah.edit', ['item' => $masterKondisiRumah]);
    }

    public function update(Request $request, MasterKondisiRumah $masterKondisiRumah)
    {
        $request->validate(['nama' => 'required|max:255', 'skor' => 'required|integer|min:1|max:3']);
        $masterKondisiRumah->update($request->only('nama', 'skor'));
        return redirect()->route('admin.master-kondisi-rumah.index')->with('success', 'Kondisi rumah berhasil diperbarui.');
    }

    public function destroy(MasterKondisiRumah $masterKondisiRumah)
    {
        if ($masterKondisiRumah->wargas()->count() > 0) {
            return back()->with('error', 'Tidak bisa dihapus, masih digunakan oleh data warga.');
        }
        $masterKondisiRumah->delete();
        return redirect()->route('admin.master-kondisi-rumah.index')->with('success', 'Kondisi rumah berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warga;

class MasterStatusProduktivitasController extends Controller
{
    public function index()
    {
        // Ambil data dari preset model Warga
        $statusList = Warga::statusProduktivitas(); // [['status'=>..., 'skor'=>...], ...]

        // Hitung jumlah warga per status_produktivitas
        $counts = Warga::selectRaw('status_produktivitas, count(*) as total')
            ->whereNotNull('status_produktivitas')
            ->groupBy('status_produktivitas')
            ->pluck('total', 'status_produktivitas');

        return view('admin.master.status-produktivitas.index', compact('statusList', 'counts'));
    }
}

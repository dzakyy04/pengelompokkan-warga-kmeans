@extends('admin.layout.app')
@section('title', 'Data Kondisi Rumah')
@section('page-title', 'Data Kondisi Rumah')
@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div><h1 class="text-2xl font-bold text-gray-900">Data Kondisi Rumah</h1><p class="text-gray-500 text-sm mt-1">Kelola data master kondisi rumah</p></div>
    <button onclick="openModal('create')" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Tambah
    </button>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200"><tr>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 text-xs uppercase w-16">No</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 text-xs uppercase">Kondisi Rumah</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 text-xs uppercase">Skor</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 text-xs uppercase">Jumlah Warga</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 text-xs uppercase w-32">Aksi</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($items as $item)
            <tr class="hover:bg-emerald-50/50 transition-colors">
                <td class="px-4 py-3 text-gray-500">{{ $loop->iteration + ($items->currentPage()-1) * $items->perPage() }}</td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ $item->nama }}</td>
                <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-lg text-xs font-semibold {{ match(true) { $item->skor >= 3 => 'bg-emerald-100 text-emerald-700', $item->skor >= 2 => 'bg-amber-100 text-amber-700', default => 'bg-rose-100 text-rose-700' } }}">{{ $item->skor }}</span></td>
                <td class="px-4 py-3 text-center"><span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">{{ $item->wargas_count }}</span></td>
                <td class="px-4 py-3 text-center"><div class="flex items-center justify-center gap-1">
                    <button onclick="openModal('edit', {{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->skor }})" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <form method="POST" action="{{ route('admin.master-kondisi-rumah.destroy', $item) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                </div></td>
            </tr>
            @empty<tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data.</td></tr>@endforelse
        </tbody>
    </table>
    @if($items->hasPages())<div class="px-4 py-3 border-t border-gray-200">{{ $items->links() }}</div>@endif
</div>

{{-- Modal --}}
<div id="modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Tambah Kondisi Rumah</h3>
                <button onclick="closeModal()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form id="modalForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kondisi Rumah <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="inputNama" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Skor (1-3) <span class="text-red-500">*</span></label>
                    <select name="skor" id="inputSkor" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="1">1 — Menumpang / Tidak Layak</option>
                        <option value="2">2 — Sewa / Cukup</option>
                        <option value="3">3 — Milik Sendiri / Layak</option>
                    </select>
                    @error('skor')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeModal()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">Batal</button>
                    <button type="submit" id="submitBtn" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>document.addEventListener('DOMContentLoaded', () => openModal('create'));</script>
@endif

<script>
function openModal(mode, id, nama, skor) {
    const modal = document.getElementById('modal');
    const form = document.getElementById('modalForm');
    const title = document.getElementById('modalTitle');
    const method = document.getElementById('methodField');
    const btn = document.getElementById('submitBtn');

    if (mode === 'edit') {
        title.textContent = 'Edit Kondisi Rumah';
        btn.textContent = 'Perbarui';
        form.action = '{{ url("admin/master-kondisi-rumah") }}/' + id;
        method.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('inputNama').value = nama || '';
        document.getElementById('inputSkor').value = skor || 1;
    } else {
        title.textContent = 'Tambah Kondisi Rumah';
        btn.textContent = 'Simpan';
        form.action = '{{ route("admin.master-kondisi-rumah.store") }}';
        method.innerHTML = '';
        document.getElementById('inputNama').value = '';
        document.getElementById('inputSkor').value = 1;
    }
    modal.classList.remove('hidden');
    setTimeout(() => document.getElementById('inputNama').focus(), 100);
}
function closeModal() { document.getElementById('modal').classList.add('hidden'); }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endsection

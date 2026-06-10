{{-- MODAL TAMBAH PRIORITAS --}}
<div id="prioritasModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-xl p-6">

        <div class="flex justify-between mb-5">
            <h2 class="text-xl font-bold">Tambah Program Prioritas</h2>
            <button onclick="closePrioritasModal()">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.program-prioritas.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-2 text-sm">Tahun</label>

                <input type="number" name="prioritas_tahun" value="{{ date('Y') }}"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">Judul Prioritas</label>

                <input type="text" name="prioritas_judul"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">Deskripsi</label>

                <textarea name="prioritas_deskripsi" rows="3"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">Status</label>

                <select name="prioritas_status"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"
                    required>
                    <option value="Aktif">Aktif</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closePrioritasModal()" class="bg-slate-700 px-4 py-2 rounded-xl">
                    Batal
                </button>

                <button class="bg-blue-600 px-4 py-2 rounded-xl">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

{{-- MODAL EDIT PRIORITAS --}}
<div id="editPrioritasModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-xl p-6">

        <div class="flex justify-between mb-5">
            <h2 class="text-xl font-bold">Edit Program Prioritas</h2>
            <button onclick="closeEditPrioritasModal()">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.program-prioritas.update') }}">
            @csrf

            <input type="hidden" id="edit_prioritas_id" name="prioritas_id">

            <div class="mb-4">
                <label class="block mb-2 text-sm">Tahun</label>

                <input type="number" id="edit_prioritas_tahun" name="prioritas_tahun"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">Judul Prioritas</label>

                <input type="text" id="edit_prioritas_judul" name="prioritas_judul"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"
                    required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">Deskripsi</label>

                <textarea id="edit_prioritas_deskripsi" name="prioritas_deskripsi" rows="3"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm">Status</label>

                <select id="edit_prioritas_status" name="prioritas_status"
                    class="w-full rounded-xl px-4 py-3 bg-slate-800 border border-slate-700"
                    required>
                    <option value="Aktif">Aktif</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditPrioritasModal()"
                    class="bg-slate-700 px-4 py-2 rounded-xl">
                    Batal
                </button>

                <button class="bg-blue-600 px-4 py-2 rounded-xl">
                    Update
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openPrioritasModal() {
        document.getElementById('prioritasModal').classList.remove('hidden');
        document.getElementById('prioritasModal').classList.add('flex');
    }

    function closePrioritasModal() {
        document.getElementById('prioritasModal').classList.add('hidden');
        document.getElementById('prioritasModal').classList.remove('flex');
    }

    function openEditPrioritasModal(item) {
        document.getElementById('edit_prioritas_id').value = item.prioritas_id;
        document.getElementById('edit_prioritas_tahun').value = item.prioritas_tahun;
        document.getElementById('edit_prioritas_judul').value = item.prioritas_judul;
        document.getElementById('edit_prioritas_deskripsi').value = item.prioritas_deskripsi ?? '';
        document.getElementById('edit_prioritas_status').value = item.prioritas_status;

        document.getElementById('editPrioritasModal').classList.remove('hidden');
        document.getElementById('editPrioritasModal').classList.add('flex');
    }

    function closeEditPrioritasModal() {
        document.getElementById('editPrioritasModal').classList.add('hidden');
        document.getElementById('editPrioritasModal').classList.remove('flex');
    }
</script>
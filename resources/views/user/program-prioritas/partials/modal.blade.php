{{-- MODAL RENCANA --}}
<div id="rencanaModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Tambah Rencana Aksi
                </h2>

                <p id="modal_prioritas_judul" class="text-sm text-slate-500"></p>
            </div>

            <button type="button" onclick="closeRencanaModal()" class="text-slate-400 hover:text-slate-800 text-xl">
                ✕
            </button>
        </div>

        <form id="rencanaForm" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Rencana Aksi
                </label>

                <input type="text" name="rencana_judul"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Target
                </label>

                <input type="number" name="rencana_target" min="1"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    placeholder="Contoh: 10" required>

                <p class="text-xs text-slate-500 mt-2">
                    Target diisi jumlah capaian yang direncanakan.
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeRencanaModal()"
                    class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                    class="px-5 py-3 rounded-2xl bg-purple-600 text-white font-semibold hover:bg-purple-700">
                    Simpan Rencana
                </button>
            </div>
        </form>

    </div>
</div>

{{-- MODAL CAPAIAN --}}
<div id="capaianModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Input Capaian
                </h2>

                <p id="modal_rencana_judul" class="text-sm text-slate-500"></p>
            </div>

            <button type="button" onclick="closeCapaianModal()" class="text-slate-400 hover:text-slate-800 text-xl">
                ✕
            </button>
        </div>

        <form id="capaianForm" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Judul Capaian
                </label>

                <input type="text" name="capaian_judul"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Deskripsi Capaian
                </label>

                <textarea name="capaian_deskripsi" rows="4"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Jumlah Capaian
                </label>

                <input type="number" name="capaian_jumlah" min="1" value="1"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                    required>

                <p class="text-xs text-slate-500 mt-2">
                    Isi jumlah capaian yang dihasilkan dari kegiatan ini.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tanggal Mulai
                    </label>

                    <input type="date" name="capaian_tanggal_mulai"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Tanggal Selesai
                    </label>

                    <input type="date" name="capaian_tanggal_selesai"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3"
                        required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Bukti Dukung Maksimal 5 File
                </label>

                <input type="file" name="capaian_file[]" multiple
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white"
                    required>

                <p class="text-xs text-slate-500 mt-2">
                    Format: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX. Maksimal 5 file.
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeCapaianModal()"
                    class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                    class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    Simpan Capaian
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openRencanaModal(item) {
        document.getElementById('modal_prioritas_judul').innerText = item.prioritas_judul;

        let action = "{{ url('/user/program-prioritas') }}/" + item.prioritas_uid + "/rencana/store";

        document.getElementById('rencanaForm').setAttribute('action', action);

        document.getElementById('rencanaModal').classList.remove('hidden');
        document.getElementById('rencanaModal').classList.add('flex');
    }

    function closeRencanaModal() {
        document.getElementById('rencanaModal').classList.add('hidden');
        document.getElementById('rencanaModal').classList.remove('flex');
        document.getElementById('rencanaForm').reset();
    }

    function openCapaianModal(rencana) {
        document.getElementById('modal_rencana_judul').innerText = rencana.rencana_judul;

        let action = "{{ url('/user/program-prioritas/rencana') }}/" + rencana.rencana_uid + "/capaian/store";

        document.getElementById('capaianForm').setAttribute('action', action);

        document.getElementById('capaianModal').classList.remove('hidden');
        document.getElementById('capaianModal').classList.add('flex');
    }

    function closeCapaianModal() {
        document.getElementById('capaianModal').classList.add('hidden');
        document.getElementById('capaianModal').classList.remove('flex');
        document.getElementById('capaianForm').reset();
    }
</script>
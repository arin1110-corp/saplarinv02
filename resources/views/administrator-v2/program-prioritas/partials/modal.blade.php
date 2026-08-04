{{-- MODAL TAMBAH PRIORITAS --}}
<div id="modalPrioritas" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-5">

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-2xl flex flex-col overflow-hidden">

        <div class="flex items-center justify-between px-7 py-5 border-b border-slate-200 dark:border-slate-700">

            <div>

                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                    Tambah Program Prioritas

                </h2>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Tambahkan program prioritas baru.

                </p>

            </div>

            <button type="button" onclick="closePrioritasModal()"
                class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                <i class="bi bi-x-lg text-lg"></i>

            </button>

        </div>

        <form method="POST" action="{{ route('admin.program-prioritas.store') }}">

            @csrf

            <div class="px-7 py-6 space-y-5">

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Tahun

                    </label>

                    <input type="number" name="prioritas_tahun" value="{{ date('Y') }}" required
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Judul Prioritas

                    </label>

                    <input type="text" name="prioritas_judul" required
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Deskripsi

                    </label>

                    <textarea name="prioritas_deskripsi" rows="4"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"></textarea>

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Status

                    </label>

                    <select name="prioritas_status" required
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="Aktif">

                            Aktif

                        </option>

                        <option value="Nonaktif">

                            Nonaktif

                        </option>

                    </select>

                </div>

            </div>

            <div class="flex justify-end gap-3 px-7 py-5 border-t border-slate-200 dark:border-slate-700">

                <button type="button" onclick="closePrioritasModal()"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                    Batal

                </button>

                <button type="submit"
                    class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5">

                    <i class="bi bi-check-circle me-2"></i>

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

{{-- MODAL EDIT PRIORITAS --}}
<div id="modalEditPrioritas"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-5">

    <div
        class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-2xl flex flex-col overflow-hidden">

        <div class="flex items-center justify-between px-7 py-5 border-b border-slate-200 dark:border-slate-700">

            <div>

                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">

                    Edit Program Prioritas

                </h2>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    Perbarui data program prioritas.

                </p>

            </div>

            <button type="button" onclick="closeEditPrioritasModal()"
                class="w-11 h-11 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">

                <i class="bi bi-x-lg text-lg"></i>

            </button>

        </div>

        <form method="POST" action="{{ route('admin.program-prioritas.update') }}">

            @csrf

            <input type="hidden" id="edit_prioritas_id" name="prioritas_id">

            <div class="px-7 py-6 space-y-5">

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Tahun

                    </label>

                    <input id="edit_prioritas_tahun" type="number" name="prioritas_tahun" required
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Judul Prioritas

                    </label>

                    <input id="edit_prioritas_judul" type="text" name="prioritas_judul" required
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Deskripsi

                    </label>

                    <textarea id="edit_prioritas_deskripsi" name="prioritas_deskripsi" rows="4"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"></textarea>

                </div>

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Status

                    </label>

                    <select id="edit_prioritas_status" name="prioritas_status" required
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        <option value="Aktif">

                            Aktif

                        </option>

                        <option value="Nonaktif">

                            Nonaktif

                        </option>

                    </select>

                </div>

            </div>

            <div class="flex justify-end gap-3 px-7 py-5 border-t border-slate-200 dark:border-slate-700">

                <button type="button" onclick="closeEditPrioritasModal()"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5">

                    Batal

                </button>

                <button type="submit"
                    class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5">

                    <i class="bi bi-save me-2"></i>

                    Update

                </button>

            </div>

        </form>

    </div>

</div>

<script>
    function openPrioritasModal() {

        $('#modalPrioritas').removeClass('hidden').addClass('flex');

        $('body').addClass('overflow-hidden');

    }

    function closePrioritasModal() {

        $('#modalPrioritas').removeClass('flex').addClass('hidden');

        $('body').removeClass('overflow-hidden');

    }

    function openEditPrioritasModal(item) {

        $('#edit_prioritas_id').val(item.prioritas_id);

        $('#edit_prioritas_tahun').val(item.prioritas_tahun);

        $('#edit_prioritas_judul').val(item.prioritas_judul);

        $('#edit_prioritas_deskripsi').val(item.prioritas_deskripsi ?? '');

        $('#edit_prioritas_status').val(item.prioritas_status);

        $('#modalEditPrioritas').removeClass('hidden').addClass('flex');

        $('body').addClass('overflow-hidden');

    }

    function closeEditPrioritasModal() {

        $('#modalEditPrioritas').removeClass('flex').addClass('hidden');

        $('body').removeClass('overflow-hidden');

    }

    $('#modalPrioritas,#modalEditPrioritas').on('click', function(e) {

        if (e.target === this) {

            $(this).removeClass('flex').addClass('hidden');

            $('body').removeClass('overflow-hidden');

        }

    });

    $(document).keydown(function(e) {

        if (e.key === 'Escape') {

            closePrioritasModal();

            closeEditPrioritasModal();

        }

    });
</script>

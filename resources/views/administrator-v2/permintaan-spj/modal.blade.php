{{-- MODAL STATUS SPJ --}}
<div id="toggleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">

    <div
        class="w-full max-w-xl rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl">

        {{-- Header --}}
        <div class="flex items-start justify-between px-6 py-5 border-b border-slate-200 dark:border-slate-700">

            <div>

                <h2 id="toggleTitle" class="text-xl font-bold text-slate-800 dark:text-white">

                    Ubah Status SPJ

                </h2>

                <p id="toggleInfo" class="mt-2 text-sm text-slate-500 dark:text-slate-400">

                </p>

            </div>

            <button type="button" onclick="closeToggleModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        {{-- Form --}}
        <form id="toggleForm" method="POST">

            @csrf

            <div class="p-6 space-y-5">

                <div>

                    <label class="block text-sm font-semibold mb-2">

                        Catatan Admin

                    </label>

                    <textarea id="toggleCatatan" name="spj_catatan_admin" rows="4" placeholder="Masukkan catatan..."
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 focus:ring-2 focus:ring-blue-500"></textarea>

                </div>

                <div
                    class="rounded-2xl border border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-900/20 p-4">

                    <div class="flex gap-3">

                        <i class="bi bi-exclamation-triangle-fill text-amber-500 text-lg"></i>

                        <div class="text-sm text-amber-700 dark:text-amber-300">

                            Jika SPJ dinonaktifkan maka nominal SPJ tidak akan
                            dihitung pada realisasi pagu.

                        </div>

                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 px-6 py-5 border-t border-slate-200 dark:border-slate-700">

                <button type="button" onclick="closeToggleModal()"
                    class="rounded-2xl border border-slate-300 dark:border-slate-700 px-5 py-3 hover:bg-slate-100 dark:hover:bg-slate-800">

                    Batal

                </button>

                <button id="toggleButton" type="submit"
                    class="rounded-2xl bg-red-600 hover:bg-red-700 text-white px-5 py-3 font-semibold">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

@push('scripts')
    <script>
        function openToggleModal(item) {
            const aktif = item.spj_status === 'Aktif';

            $('#toggleTitle').text(
                aktif ?
                'Nonaktifkan SPJ' :
                'Aktifkan SPJ'
            );

            $('#toggleInfo').html(`
        <div class="font-semibold">${item.spj_uraian ?? '-'}</div>
        <div class="text-blue-600 mt-1">
            Rp ${Number(item.spj_nominal ?? 0).toLocaleString('id-ID')}
        </div>
    `);

            $('#toggleCatatan').val(item.spj_catatan_admin ?? '');

            $('#toggleForm').attr(
                'action',
                "{{ url('/admin/spj/permintaan') }}/" +
                item.spj_uid +
                "/toggle"
            );

            $('#toggleButton')
                .removeClass('bg-red-600 bg-green-600 hover:bg-red-700 hover:bg-green-700')
                .addClass(
                    aktif ?
                    'bg-red-600 hover:bg-red-700' :
                    'bg-green-600 hover:bg-green-700'
                )
                .text(
                    aktif ?
                    'Nonaktifkan' :
                    'Aktifkan'
                );

            $('#toggleModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closeToggleModal() {
            $('#toggleModal')
                .addClass('hidden')
                .removeClass('flex');

            $('#toggleForm')[0].reset();
        }

        window.addEventListener('keydown', function(e) {

            if (e.key === 'Escape') {

                closeToggleModal();

            }

        });

        document.getElementById('toggleModal').addEventListener('click', function(e) {

            if (e.target === this) {

                closeToggleModal();

            }

        });
    </script>
@endpush

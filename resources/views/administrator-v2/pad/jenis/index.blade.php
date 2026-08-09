@extends('administrator-v2.layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Master Jenis PAD
                </h1>

                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Kelola jenis penerimaan Pendapatan Asli Daerah.
                </p>
            </div>

            <button onclick="openJenisModal()"
                class="inline-flex items-center justify-center gap-2
                   px-5 py-3 rounded-2xl
                   bg-blue-600 hover:bg-blue-700
                   text-white font-semibold">

                <i class="bi bi-plus-lg"></i>

                Tambah Jenis

            </button>

        </div>


        @if (session('success'))
            <div
                class="rounded-2xl bg-green-50 dark:bg-green-900/20
                    border border-green-200 dark:border-green-800
                    px-5 py-4 text-green-700 dark:text-green-300">

                {{ session('success') }}

            </div>
        @endif


        <div
            class="bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-800
                rounded-3xl overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50 dark:bg-slate-800">

                        <tr>

                            <th class="px-5 py-4 text-left">
                                No
                            </th>

                            <th class="px-5 py-4 text-left">
                                Kode
                            </th>

                            <th class="px-5 py-4 text-left">
                                Jenis PAD
                            </th>

                            <th class="px-5 py-4 text-left">
                                Keterangan
                            </th>

                            <th class="px-5 py-4 text-center">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">

                        @forelse($jenis as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">

                                <td class="px-5 py-4 text-slate-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-4 font-medium">
                                    {{ $item->pad_jenis_kode ?: '-' }}
                                </td>

                                <td class="px-5 py-4 font-semibold">
                                    {{ $item->pad_jenis_nama }}
                                </td>

                                <td class="px-5 py-4 text-slate-500">
                                    {{ $item->pad_jenis_keterangan ?: '-' }}
                                </td>

                                <td class="px-5 py-4 text-center">

                                    @if ($item->pad_jenis_status)
                                        <span
                                            class="px-3 py-1.5 rounded-full
                                                 bg-green-100 text-green-700
                                                 dark:bg-green-900/30
                                                 dark:text-green-300">

                                            Aktif

                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1.5 rounded-full
                                                 bg-slate-100 text-slate-500
                                                 dark:bg-slate-800
                                                 dark:text-slate-400">

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex justify-end gap-2">

                                        <button onclick='editJenis(@json($item))'
                                            class="w-10 h-10 rounded-xl
                                               bg-blue-50 text-blue-600
                                               hover:bg-blue-100
                                               dark:bg-blue-900/20
                                               dark:text-blue-400">

                                            <i class="bi bi-pencil"></i>

                                        </button>

                                        <form method="POST"
                                            action="{{ route('admin.pad.jenis.status', $item->pad_jenis_uid) }}">

                                            @csrf

                                            <button
                                                class="w-10 h-10 rounded-xl
                                                   bg-slate-100 text-slate-600
                                                   hover:bg-slate-200
                                                   dark:bg-slate-800
                                                   dark:text-slate-300">

                                                <i class="bi bi-power"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="px-5 py-12 text-center
                                       text-slate-500">

                                    Belum ada jenis PAD.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- MODAL -->

    <div id="jenisModal"
        class="fixed inset-0 z-50 hidden
           bg-black/50 backdrop-blur-sm
           items-center justify-center p-4">

        <div
            class="w-full max-w-lg
               bg-white dark:bg-slate-900
               rounded-3xl shadow-2xl
               max-h-[90vh] overflow-y-auto">

            <div
                class="p-6 border-b
                    border-slate-200 dark:border-slate-800
                    flex items-center justify-between">

                <div>

                    <h2 id="jenisModalTitle"
                        class="text-xl font-bold
                           text-slate-900 dark:text-white">

                        Tambah Jenis PAD

                    </h2>

                </div>

                <button onclick="closeJenisModal()"
                    class="w-10 h-10 rounded-xl
                       hover:bg-slate-100
                       dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <form id="jenisForm" method="POST" action="{{ route('admin.pad.jenis.store') }}">

                @csrf

                <div id="jenisMethod"></div>

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Kode
                        </label>

                        <input type="text" name="pad_jenis_kode" id="pad_jenis_kode"
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            placeholder="Contoh: PAD-01">

                    </div>


                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Jenis PAD
                        </label>

                        <input type="text" name="pad_jenis_nama" id="pad_jenis_nama" required
                            class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            placeholder="Nama jenis PAD">

                    </div>


                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Keterangan
                        </label>

                        <textarea name="pad_jenis_keterangan" id="pad_jenis_keterangan" rows="3"
                             class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"
                            placeholder="Keterangan (opsional)"></textarea>

                    </div>

                </div>


                <div
                    class="p-6 border-t
                        border-slate-200 dark:border-slate-800
                        flex justify-end gap-3">

                    <button type="button" onclick="closeJenisModal()"
                        class="px-5 py-3 rounded-2xl
                           bg-slate-100 hover:bg-slate-200
                           dark:bg-slate-800
                           dark:hover:bg-slate-700">

                        Batal

                    </button>

                    <button type="submit"
                        class="px-5 py-3 rounded-2xl
                           bg-blue-600 hover:bg-blue-700
                           text-white font-semibold">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>


    <script>
        function openJenisModal() {
            document.getElementById('jenisModal')
                .classList.remove('hidden');

            document.getElementById('jenisModal')
                .classList.add('flex');

            document.getElementById('jenisModalTitle')
                .innerText = 'Tambah Jenis PAD';

            document.getElementById('jenisForm')
                .action =
                "{{ route('admin.pad.jenis.store') }}";

            document.getElementById('jenisMethod')
                .innerHTML = '';

            document.getElementById('pad_jenis_kode')
                .value = '';

            document.getElementById('pad_jenis_nama')
                .value = '';

            document.getElementById('pad_jenis_keterangan')
                .value = '';
        }


        function editJenis(item) {
            openJenisModal();

            document.getElementById('jenisModalTitle')
                .innerText = 'Edit Jenis PAD';

            document.getElementById('jenisForm')
                .action =
                `/admin/pad/jenis/${item.pad_jenis_uid}`;

            document.getElementById('jenisMethod')
                .innerHTML =
                '@method('PUT')';

            document.getElementById('pad_jenis_kode')
                .value =
                item.pad_jenis_kode ?? '';

            document.getElementById('pad_jenis_nama')
                .value =
                item.pad_jenis_nama ?? '';

            document.getElementById('pad_jenis_keterangan')
                .value =
                item.pad_jenis_keterangan ?? '';
        }


        function closeJenisModal() {
            document.getElementById('jenisModal')
                .classList.add('hidden');

            document.getElementById('jenisModal')
                .classList.remove('flex');
        }
    </script>
@endsection

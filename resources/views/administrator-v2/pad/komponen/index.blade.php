@extends('administrator-v2.layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row
                sm:items-center sm:justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold
                       text-slate-900 dark:text-white">

                    Master Komponen PAD

                </h1>

                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">

                    Kelola komponen penerimaan PAD.

                </p>

            </div>

            <button onclick="openKomponenModal()"
                class="px-5 py-3 rounded-2xl
                   bg-blue-600 hover:bg-blue-700
                   text-white font-semibold">

                <i class="bi bi-plus-lg me-1"></i>

                Tambah Komponen

            </button>

        </div>


        @if (session('success'))
            <div
                class="rounded-2xl
                    bg-green-50 dark:bg-green-900/20
                    border border-green-200
                    dark:border-green-800
                    px-5 py-4
                    text-green-700
                    dark:text-green-300">

                {{ session('success') }}

            </div>
        @endif


        <div
            class="bg-white dark:bg-slate-900
                border border-slate-200
                dark:border-slate-800
                rounded-3xl overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50 dark:bg-slate-800">

                        <tr>

                            <th class="px-5 py-4 text-left">
                                No
                            </th>

                            <th class="px-5 py-4 text-left">
                                Jenis
                            </th>

                            <th class="px-5 py-4 text-left">
                                Kode
                            </th>

                            <th class="px-5 py-4 text-left">
                                Komponen
                            </th>

                            <th class="px-5 py-4 text-center">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y
                             divide-slate-100
                             dark:divide-slate-800">

                        @forelse($komponen as $item)
                            <tr class="hover:bg-slate-50
                                   dark:hover:bg-slate-800/50">

                                <td class="px-5 py-4 text-slate-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-4">

                                    <span
                                        class="px-3 py-1 rounded-full
                                             bg-blue-50 text-blue-700
                                             dark:bg-blue-900/30
                                             dark:text-blue-300">

                                        {{ $item->jenis->pad_jenis_nama ?? '-' }}

                                    </span>

                                </td>

                                <td class="px-5 py-4">
                                    {{ $item->pad_komponen_kode ?: '-' }}
                                </td>

                                <td class="px-5 py-4 font-semibold">
                                    {{ $item->pad_komponen_nama }}
                                </td>

                                <td class="px-5 py-4 text-center">

                                    @if ($item->pad_komponen_status)
                                        <span
                                            class="px-3 py-1.5 rounded-full
                                                 bg-green-100
                                                 text-green-700
                                                 dark:bg-green-900/30
                                                 dark:text-green-300">

                                            Aktif

                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1.5 rounded-full
                                                 bg-slate-100
                                                 text-slate-500
                                                 dark:bg-slate-800
                                                 dark:text-slate-400">

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex justify-end gap-2">

                                        <button onclick='editKomponen(@json($item))'
                                            class="w-10 h-10 rounded-xl
                                               bg-blue-50 text-blue-600
                                               dark:bg-blue-900/20
                                               dark:text-blue-400">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        <form method="POST"
                                            action="{{ route('admin.pad.komponen.status', $item->pad_komponen_uid) }}">

                                            @csrf

                                            <button
                                                class="w-10 h-10 rounded-xl
                                                   bg-slate-100
                                                   text-slate-600
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

                                    Belum ada komponen PAD.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- MODAL -->

    <div id="komponenModal"
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
                    border-slate-200
                    dark:border-slate-800
                    flex justify-between">

                <h2 id="komponenModalTitle" class="text-xl font-bold">

                    Tambah Komponen PAD

                </h2>

                <button onclick="closeKomponenModal()"
                    class="w-10 h-10 rounded-xl
                       hover:bg-slate-100
                       dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            <form id="komponenForm" method="POST"
                action="{{ route('admin.pad.komponen.store') }}">

                @csrf

                <div id="komponenMethod"></div>

                <div class="p-6 space-y-5">

                    <div>

                        <label class="block text-sm
                                  font-semibold mb-2">

                            Jenis PAD

                        </label>

                        <select name="pad_komponen_jenis" id="pad_komponen_jenis" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="">
                                Pilih Jenis PAD
                            </option>

                            @foreach ($jenis as $item)
                                <option value="{{ $item->pad_jenis_id }}">

                                    {{ $item->pad_jenis_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div>

                        <label class="block text-sm
                                  font-semibold mb-2">

                            Kode Komponen

                        </label>

                        <input type="text" name="pad_komponen_kode" id="pad_komponen_kode"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>


                    <div>

                        <label class="block text-sm
                                  font-semibold mb-2">

                            Nama Komponen

                        </label>

                        <input type="text" name="pad_komponen_nama" id="pad_komponen_nama" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>


                    <div>

                        <label class="block text-sm
                                  font-semibold mb-2">

                            Keterangan

                        </label>

                        <textarea name="pad_komponen_keterangan" id="pad_komponen_keterangan" rows="3"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"></textarea>

                    </div>

                </div>


                <div
                    class="p-6 border-t
                        border-slate-200
                        dark:border-slate-800
                        flex justify-end gap-3">

                    <button type="button" onclick="closeKomponenModal()"
                        class="px-5 py-3 rounded-2xl
                           bg-slate-100
                           dark:bg-slate-800">

                        Batal

                    </button>

                    <button type="submit"
                        class="px-5 py-3 rounded-2xl
                           bg-blue-600
                           text-white font-semibold">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>


    <script>
        function openKomponenModal() {
            const modal =
                document.getElementById(
                    'komponenModal'
                );

            modal.classList.remove('hidden');

            modal.classList.add('flex');

            document.getElementById(
                    'komponenModalTitle'
                ).innerText =
                'Tambah Komponen PAD';

            document.getElementById(
                    'komponenForm'
                ).action =
                "{{ route('admin.pad.komponen.store') }}";

            document.getElementById(
                'komponenMethod'
            ).innerHTML = '';

            document.getElementById(
                'pad_komponen_jenis'
            ).value = '';

            document.getElementById(
                'pad_komponen_kode'
            ).value = '';

            document.getElementById(
                'pad_komponen_nama'
            ).value = '';

            document.getElementById(
                'pad_komponen_keterangan'
            ).value = '';
        }


        function editKomponen(item) {
            openKomponenModal();

            document.getElementById(
                    'komponenModalTitle'
                ).innerText =
                'Edit Komponen PAD';

            document.getElementById(
                    'komponenForm'
                ).action =
                `/admin/pad/komponen/${item.pad_komponen_uid}`;

            document.getElementById(
                    'komponenMethod'
                ).innerHTML =
                '@method('PUT')';

            document.getElementById(
                    'pad_komponen_jenis'
                ).value =
                item.pad_komponen_jenis;

            document.getElementById(
                    'pad_komponen_kode'
                ).value =
                item.pad_komponen_kode ?? '';

            document.getElementById(
                    'pad_komponen_nama'
                ).value =
                item.pad_komponen_nama ?? '';

            document.getElementById(
                    'pad_komponen_keterangan'
                ).value =
                item.pad_komponen_keterangan ?? '';
        }


        function closeKomponenModal() {
            const modal =
                document.getElementById(
                    'komponenModal'
                );

            modal.classList.add('hidden');

            modal.classList.remove('flex');
        }
    </script>
@endsection

@extends('administrator-v2.layouts.app')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}

        <div class="flex flex-col lg:flex-row
                lg:items-center lg:justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold
                       text-slate-900 dark:text-white">

                    Target Penerimaan PAD

                </h1>

                <p class="text-sm text-slate-500
                      dark:text-slate-400 mt-1">

                    Pengaturan target dan rencana penerimaan PAD tahunan.

                </p>

            </div>


            <div class="flex flex-wrap gap-3">

                <form method="GET" action="{{ route('admin.pad.target.index') }}" class="flex gap-2">

                    <select name="tahun" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                        @for ($i = now()->year - 2; $i <= now()->year + 2; $i++)
                            <option value="{{ $i }}" @selected($tahun == $i)>

                                Tahun {{ $i }}

                            </option>
                        @endfor

                    </select>

                </form>


                <button onclick="openTargetModal()"
                    class="px-5 py-3 rounded-2xl
                       bg-blue-600 hover:bg-blue-700
                       text-white font-semibold">

                    <i class="bi bi-plus-lg me-1"></i>

                    Tambah Target

                </button>

            </div>

        </div>


        {{-- ALERT --}}

        @if (session('success'))
            <div
                class="rounded-2xl
                    bg-green-50
                    dark:bg-green-900/20
                    border border-green-200
                    dark:border-green-800
                    px-5 py-4
                    text-green-700
                    dark:text-green-300">

                {{ session('success') }}

            </div>
        @endif


        @if (session('error'))
            <div
                class="rounded-2xl
                    bg-red-50
                    dark:bg-red-900/20
                    border border-red-200
                    dark:border-red-800
                    px-5 py-4
                    text-red-700
                    dark:text-red-300">

                {{ session('error') }}

            </div>
        @endif


        {{-- SUMMARY --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div
                class="rounded-3xl
                    bg-white dark:bg-slate-900
                    border border-slate-200
                    dark:border-slate-800
                    p-6">

                <div class="text-sm text-slate-500
                        dark:text-slate-400">

                    Total Target {{ $tahun }}

                </div>

                <div class="mt-2 text-2xl font-bold
                        text-slate-900 dark:text-white">

                    Rp
                    {{ number_format($totalTarget, 0, ',', '.') }}

                </div>

            </div>


            <div
                class="rounded-3xl
                    bg-white dark:bg-slate-900
                    border border-slate-200
                    dark:border-slate-800
                    p-6">

                <div class="text-sm text-slate-500
                        dark:text-slate-400">

                    Total Rencana

                </div>

                <div class="mt-2 text-2xl font-bold
                        text-slate-900 dark:text-white">

                    Rp
                    {{ number_format($totalRencana, 0, ',', '.') }}

                </div>

            </div>


            <div
                class="rounded-3xl
                    bg-white dark:bg-slate-900
                    border border-slate-200
                    dark:border-slate-800
                    p-6">

                <div class="text-sm text-slate-500
                        dark:text-slate-400">

                    Unit Terdaftar

                </div>

                <div class="mt-2 text-2xl font-bold
                        text-slate-900 dark:text-white">

                    {{ $jumlahUnit }}

                    <span class="text-sm font-normal
                             text-slate-500">

                        Unit

                    </span>

                </div>

            </div>

        </div>


        {{-- TABLE --}}

        <div
            class="rounded-3xl
                bg-white dark:bg-slate-900
                border border-slate-200
                dark:border-slate-800
                overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50
                           dark:bg-slate-800">

                        <tr>

                            <th class="px-5 py-4 text-left">
                                No
                            </th>

                            <th class="px-5 py-4 text-left">
                                Jenis PAD
                            </th>

                            <th class="px-5 py-4 text-left">
                                Komponen
                            </th>

                            <th class="px-5 py-4 text-left">
                                Unit
                            </th>

                            <th class="px-5 py-4 text-right">
                                Target
                            </th>

                            <th class="px-5 py-4 text-right">
                                Rencana
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

                        @forelse($targets as $item)
                            <tr class="hover:bg-slate-50
                                   dark:hover:bg-slate-800/50">

                                <td class="px-5 py-4
                                       text-slate-500">

                                    {{ $loop->iteration }}

                                </td>


                                <td class="px-5 py-4">

                                    {{ $item->jenis->pad_jenis_nama ?? '-' }}

                                </td>


                                <td class="px-5 py-4 font-semibold">

                                    {{ $item->komponen->pad_komponen_nama ?? '-' }}

                                </td>


                                <td class="px-5 py-4">

                                    <div class="font-medium">

                                        {{ $item->pad_target_unit_nama }}

                                    </div>

                                    @if ($item->pad_target_unit_kode)
                                        <div class="text-xs
                                                text-slate-500">

                                            {{ $item->pad_target_unit_kode }}

                                        </div>
                                    @endif

                                </td>


                                <td class="px-5 py-4 text-right
                                       font-semibold">

                                    Rp
                                    {{ number_format($item->pad_target_nominal, 0, ',', '.') }}

                                </td>


                                <td class="px-5 py-4 text-right">

                                    Rp
                                    {{ number_format($item->pad_target_rencana, 0, ',', '.') }}

                                </td>


                                <td class="px-5 py-4 text-center">

                                    @if ($item->pad_target_status)
                                        <span
                                            class="px-3 py-1.5
                                               rounded-full
                                               bg-green-100
                                               text-green-700
                                               dark:bg-green-900/30
                                               dark:text-green-300">

                                            Aktif

                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1.5
                                               rounded-full
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

                                        <button onclick='editTarget(@json($item))'
                                            class="w-10 h-10
                                               rounded-xl
                                               bg-blue-50
                                               text-blue-600
                                               dark:bg-blue-900/20
                                               dark:text-blue-400">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        <form method="POST"
                                            action="{{ route('admin.pad.target.status', $item->pad_target_uid) }}">

                                            @csrf

                                            <button
                                                class="w-10 h-10
                                                   rounded-xl
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

                                <td colspan="8"
                                    class="px-5 py-14
                                       text-center
                                       text-slate-500">

                                    Belum ada target PAD
                                    untuk tahun {{ $tahun }}.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL TARGET --}}
    {{-- ========================================================= --}}

    <div id="targetModal"
        class="fixed inset-0 z-50 hidden
           bg-black/50 backdrop-blur-sm
           items-center justify-center p-4">

        <div
            class="w-full max-w-2xl
               bg-white dark:bg-slate-900
               rounded-3xl shadow-2xl
               max-h-[90vh]
               overflow-y-auto">

            {{-- HEADER --}}

            <div
                class="p-6 border-b
                   border-slate-200
                   dark:border-slate-800
                   flex items-center justify-between">

                <div>

                    <h2 id="targetModalTitle"
                        class="text-xl font-bold
                           text-slate-900
                           dark:text-white">

                        Tambah Target PAD

                    </h2>

                    <p
                        class="text-sm
                          text-slate-500
                          dark:text-slate-400 mt-1">

                        Target penerimaan untuk satu unit.

                    </p>

                </div>


                <button type="button" onclick="closeTargetModal()"
                    class="w-10 h-10
                       rounded-xl
                       hover:bg-slate-100
                       dark:hover:bg-slate-800">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            {{-- FORM --}}

            <form id="targetForm" method="POST" action="{{ route('admin.pad.target.store') }}">

                @csrf

                <div id="targetMethod"></div>


                <div class="p-6 space-y-5">


                    {{-- TAHUN --}}

                    <div>

                        <label class="block text-sm
                               font-semibold mb-2">

                            Tahun

                        </label>

                        <input type="number" name="pad_target_tahun" id="pad_target_tahun" value="{{ $tahun }}"
                            min="2000" max="2100" required
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>


                    {{-- JENIS --}}

                    <div>

                        <label class="block text-sm
                               font-semibold mb-2">

                            Jenis PAD

                        </label>

                        <select name="pad_target_jenis" id="pad_target_jenis" required onchange="loadKomponen()"
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


                    {{-- KOMPONEN --}}

                    <div>

                        <label class="block text-sm
                               font-semibold mb-2">

                            Komponen PAD

                        </label>

                        <select name="pad_target_komponen" id="pad_target_komponen"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            <option value="">
                                Pilih Komponen
                            </option>

                        </select>

                    </div>


                    {{-- UNIT --}}

                    <div>

                        <label class="block text-sm
                               font-semibold mb-2">

                            Unit

                        </label>

                        <input type="text" name="pad_target_unit" id="pad_target_unit" required
                            placeholder="Kode/unit internal"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>


                    <div>

                        <label class="block text-sm
                               font-semibold mb-2">

                            Nama Unit

                        </label>

                        <input type="text" name="pad_target_unit_nama" id="pad_target_unit_nama" required
                            placeholder="Contoh: Bidang Kesenian"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>


                    <div>

                        <label class="block text-sm
                               font-semibold mb-2">

                            Kode Unit

                        </label>

                        <input type="text" name="pad_target_unit_kode" id="pad_target_unit_kode"
                            placeholder="Opsional"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                    </div>


                    {{-- TARGET + RENCANA --}}

                    <div class="grid md:grid-cols-2 gap-4">

                        <div>

                            <label class="block text-sm
                                   font-semibold mb-2">

                                Target Penerimaan

                            </label>

                            <div class="relative">


                                <input type="number" name="pad_target_nominal" id="pad_target_nominal" min="0" placeholder="Rp 0"
                                    step="0.01" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            </div>

                        </div>


                        <div>

                            <label class="block text-sm
                                   font-semibold mb-2">

                                Rencana Penerimaan

                            </label>

                            <div class="relative">

                                <input type="number" name="pad_target_rencana" id="pad_target_rencana" min="0" placeholder="Rp 0"
                                    step="0.01" required
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3">

                            </div>

                        </div>

                    </div>


                    {{-- KETERANGAN --}}

                    <div>

                        <label class="block text-sm
                               font-semibold mb-2">

                            Keterangan

                        </label>

                        <textarea name="pad_target_keterangan" id="pad_target_keterangan" rows="3"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3"></textarea>

                    </div>

                </div>


                {{-- FOOTER --}}

                <div
                    class="p-6 border-t
                       border-slate-200
                       dark:border-slate-800
                       flex justify-end gap-3">

                    <button type="button" onclick="closeTargetModal()"
                        class="px-5 py-3
                           rounded-2xl
                           bg-slate-100
                           hover:bg-slate-200
                           dark:bg-slate-800
                           dark:hover:bg-slate-700">

                        Batal

                    </button>


                    <button type="submit"
                        class="px-5 py-3
                           rounded-2xl
                           bg-blue-600
                           hover:bg-blue-700
                           text-white
                           font-semibold">

                        Simpan Target

                    </button>

                </div>

            </form>

        </div>

    </div>


    @php
        $padKomponenData = $jenis->mapWithKeys(function ($item) {
            return [
                $item->pad_jenis_id => $item->komponen
                    ->map(function ($komponen) {
                        return [
                            'id' => $komponen->pad_komponen_id,
                            'nama' => $komponen->pad_komponen_nama,
                            'kode' => $komponen->pad_komponen_kode,
                        ];
                    })
                    ->values(),
            ];
        });
    @endphp

    <script>
        const padKomponenData = @json($padKomponenData);


        function openTargetModal() {

            const modal = document.getElementById('targetModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.getElementById('targetModalTitle').innerText =
                'Tambah Target PAD';

            document.getElementById('targetForm').action =
                "{{ route('admin.pad.target.store') }}";

            document.getElementById('targetMethod').innerHTML = '';

            document.getElementById('pad_target_tahun').value =
                "{{ $tahun }}";

            document.getElementById('pad_target_jenis').value = '';

            document.getElementById('pad_target_komponen').innerHTML =
                '<option value="">Pilih Komponen</option>';

            document.getElementById('pad_target_unit').value = '';

            document.getElementById('pad_target_unit_nama').value = '';

            document.getElementById('pad_target_unit_kode').value = '';

            document.getElementById('pad_target_nominal').value = '';

            document.getElementById('pad_target_rencana').value = '';

            document.getElementById('pad_target_keterangan').value = '';
        }


        function loadKomponen(selected = null) {

            const jenis =
                document.getElementById('pad_target_jenis').value;

            const select =
                document.getElementById('pad_target_komponen');

            select.innerHTML =
                '<option value="">Pilih Komponen</option>';

            if (!jenis) {
                return;
            }

            const list =
                padKomponenData[jenis] ?? [];

            list.forEach(item => {

                const option =
                    document.createElement('option');

                option.value = item.id;

                option.textContent =
                    item.kode ?
                    `${item.kode} - ${item.nama}` :
                    item.nama;

                if (
                    selected !== null &&
                    String(selected) === String(item.id)
                ) {
                    option.selected = true;
                }

                select.appendChild(option);
            });
        }


        function editTarget(item) {

            openTargetModal();

            document.getElementById('targetModalTitle').innerText =
                'Edit Target PAD';

            document.getElementById('targetForm').action =
                `/admin/pad/target/${item.pad_target_uid}`;

            document.getElementById('targetMethod').innerHTML =
                '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('pad_target_tahun').value =
                item.pad_target_tahun;

            document.getElementById('pad_target_jenis').value =
                item.pad_target_jenis;

            loadKomponen(item.pad_target_komponen);

            document.getElementById('pad_target_unit').value =
                item.pad_target_unit ?? '';

            document.getElementById('pad_target_unit_nama').value =
                item.pad_target_unit_nama ?? '';

            document.getElementById('pad_target_unit_kode').value =
                item.pad_target_unit_kode ?? '';

            document.getElementById('pad_target_nominal').value =
                item.pad_target_nominal ?? 0;

            document.getElementById('pad_target_rencana').value =
                item.pad_target_rencana ?? 0;

            document.getElementById('pad_target_keterangan').value =
                item.pad_target_keterangan ?? '';
        }


        function closeTargetModal() {

            const modal =
                document.getElementById('targetModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }


        document.getElementById('targetModal')
            ?.addEventListener('click', function(e) {

                if (e.target === this) {
                    closeTargetModal();
                }

            });
    </script>
@endsection

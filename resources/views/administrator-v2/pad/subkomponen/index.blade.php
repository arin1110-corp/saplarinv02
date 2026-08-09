@extends('administrator-v2.layouts.app')

@section('title', 'Master Subkomponen PAD')
@section('page_title', 'Master Subkomponen PAD')
@section('breadcrumb', 'Subkomponen PAD')

@section('content')

    <div class="space-y-6">

        {{-- SUCCESS --}}
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20
                    border border-green-200 dark:border-green-800
                    text-green-700 dark:text-green-400
                    px-5 py-4 rounded-2xl">

                {{ session('success') }}

            </div>
        @endif


        {{-- ERROR --}}
        @if ($errors->any())
            <div
                class="bg-red-50 dark:bg-red-900/20
                    border border-red-200 dark:border-red-800
                    text-red-700 dark:text-red-400
                    px-5 py-4 rounded-2xl">

                <ul class="list-disc pl-5 space-y-1">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif


        {{-- HEADER --}}
        <div
            class="flex flex-col md:flex-row
                md:items-center
                md:justify-between
                gap-4">

            <div>

                <h1 class="text-2xl font-bold
                       text-slate-900 dark:text-white">

                    Master Subkomponen PAD

                </h1>

                <p class="mt-1
                      text-slate-500 dark:text-slate-400">

                    Kelola subkomponen penerimaan PAD
                    yang akan dipilih operator.

                </p>

            </div>


            <button type="button" onclick="openSubkomponenModal()"
                class="inline-flex items-center justify-center
                   px-5 py-3
                   rounded-2xl
                   bg-blue-600
                   hover:bg-blue-700
                   text-white
                   font-semibold
                   transition">

                <i class="bi bi-plus-lg me-1"></i>

                Tambah Subkomponen

            </button>

        </div>


        {{-- TABLE CARD --}}
        <div
            class="bg-white dark:bg-slate-900
                border border-slate-200 dark:border-slate-800
                rounded-3xl
                overflow-hidden">


            {{-- SEARCH AREA --}}
            <div class="p-6
                    border-b border-slate-200
                    dark:border-slate-800">

                <form method="GET" action="{{ route('admin.pad.subkomponen.index') }}">

                    <div class="relative max-w-md">

                        <i
                            class="bi bi-search
                              absolute left-4 top-1/2
                              -translate-y-1/2
                              text-slate-400
                              dark:text-slate-500"></i>

                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari subkomponen..."
                            class="w-full
                               pl-11 pr-4 py-3
                               rounded-2xl

                               bg-white
                               dark:bg-slate-800

                               border
                               border-slate-300
                               dark:border-slate-700

                               text-slate-900
                               dark:text-white

                               placeholder-slate-400
                               dark:placeholder-slate-500

                               focus:ring-2
                               focus:ring-blue-500

                               focus:border-blue-500
                               dark:focus:border-blue-500

                               outline-none
                               transition">

                    </div>

                </form>

            </div>


            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr
                            class="border-b
                               border-slate-200
                               dark:border-slate-800

                               bg-slate-50
                               dark:bg-slate-800/50

                               text-left
                               text-slate-500
                               dark:text-slate-400">

                            <th class="py-4 px-4 whitespace-nowrap">
                                No
                            </th>

                            <th class="py-4 px-4 whitespace-nowrap">
                                Kode
                            </th>

                            <th class="py-4 px-4">
                                Subkomponen
                            </th>

                            <th class="py-4 px-4">
                                Komponen
                            </th>

                            <th class="py-4 px-4 whitespace-nowrap">
                                Status
                            </th>

                            <th class="py-4 px-4 text-right whitespace-nowrap">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($subkomponen as $item)
                            <tr
                                class="border-b
                                   border-slate-100
                                   dark:border-slate-800

                                   hover:bg-slate-50
                                   dark:hover:bg-slate-800/50

                                   transition">

                                {{-- NO --}}
                                <td
                                    class="py-4 px-4
                                       text-slate-500
                                       dark:text-slate-400">

                                    {{ $subkomponen->firstItem() + $loop->index }}

                                </td>


                                {{-- KODE --}}
                                <td class="py-4 px-4">

                                    @if ($item->pad_subkomponen_kode)
                                        <span
                                            class="font-mono
                                                 text-xs
                                                 text-slate-600
                                                 dark:text-slate-300">

                                            {{ $item->pad_subkomponen_kode }}

                                        </span>
                                    @else
                                        <span
                                            class="text-slate-400
                                                 dark:text-slate-600">

                                            -

                                        </span>
                                    @endif

                                </td>


                                {{-- SUBKOMPONEN --}}
                                <td class="py-4 px-4">

                                    <div
                                        class="font-semibold
                                            text-slate-800
                                            dark:text-white">

                                        {{ $item->pad_subkomponen_nama }}

                                    </div>


                                    @if ($item->pad_subkomponen_keterangan)
                                        <div
                                            class="text-xs
                                                text-slate-400
                                                dark:text-slate-500
                                                mt-1
                                                max-w-md">

                                            {{ $item->pad_subkomponen_keterangan }}

                                        </div>
                                    @endif

                                </td>


                                {{-- KOMPONEN --}}
                                <td class="py-4 px-4">

                                    <div
                                        class="text-slate-700
                                            dark:text-slate-300">

                                        {{ $item->komponen->pad_komponen_nama ?? '-' }}

                                    </div>


                                    @if ($item->komponen?->pad_komponen_kode)
                                        <div
                                            class="text-xs
                                                text-slate-400
                                                dark:text-slate-500
                                                mt-1">

                                            {{ $item->komponen->pad_komponen_kode }}

                                        </div>
                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td class="py-4 px-4">

                                    @if ($item->pad_subkomponen_status)
                                        <span
                                            class="inline-flex
                                                 items-center
                                                 gap-1.5
                                                 px-3 py-1.5
                                                 rounded-full

                                                 bg-green-50
                                                 dark:bg-green-900/20

                                                 text-green-700
                                                 dark:text-green-400

                                                 text-xs
                                                 font-semibold">

                                            <span
                                                class="w-1.5 h-1.5
                                                     rounded-full
                                                     bg-green-500"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span
                                            class="inline-flex
                                                 items-center
                                                 gap-1.5
                                                 px-3 py-1.5
                                                 rounded-full

                                                 bg-slate-100
                                                 dark:bg-slate-800

                                                 text-slate-500
                                                 dark:text-slate-400

                                                 text-xs
                                                 font-semibold">

                                            <span
                                                class="w-1.5 h-1.5
                                                     rounded-full
                                                     bg-slate-400"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="py-4 px-4">

                                    <div
                                        class="flex
                                            justify-end
                                            gap-2">


                                        {{-- EDIT --}}
                                        <button type="button" onclick='editSubkomponen(@json($item))'
                                            class="w-10 h-10
                                               inline-flex
                                               items-center
                                               justify-center

                                               rounded-xl

                                               bg-blue-50
                                               dark:bg-blue-900/20

                                               text-blue-600
                                               dark:text-blue-400

                                               hover:bg-blue-100
                                               dark:hover:bg-blue-900/40

                                               transition">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- TOGGLE --}}
                                        <form method="POST"
                                            action="{{ route('admin.pad.subkomponen.toggle', $item->pad_subkomponen_uid) }}">

                                            @csrf

                                            @method('PATCH')


                                            <button type="submit"
                                                class="w-10 h-10
                                                   inline-flex
                                                   items-center
                                                   justify-center

                                                   rounded-xl

                                                   transition

                                                   {{ $item->pad_subkomponen_status
                                                       ? 'bg-red-50 dark:bg-red-900/20
                                                                                                             text-red-600 dark:text-red-400
                                                                                                             hover:bg-red-100 dark:hover:bg-red-900/40'
                                                       : 'bg-green-50 dark:bg-green-900/20
                                                                                                             text-green-600 dark:text-green-400
                                                                                                             hover:bg-green-100 dark:hover:bg-green-900/40' }}">

                                                <i
                                                    class="bi {{ $item->pad_subkomponen_status ? 'bi-x-circle' : 'bi-check-circle' }}"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="py-14 px-4
                                       text-center
                                       text-slate-500
                                       dark:text-slate-400">

                                    <div
                                        class="flex
                                            flex-col
                                            items-center
                                            gap-3">

                                        <div
                                            class="w-14 h-14
                                                rounded-2xl
                                                bg-slate-100
                                                dark:bg-slate-800
                                                flex
                                                items-center
                                                justify-center">

                                            <i
                                                class="bi bi-diagram-3
                                                  text-2xl
                                                  text-slate-400
                                                  dark:text-slate-500"></i>

                                        </div>

                                        <div>
                                            Belum ada subkomponen.
                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if ($subkomponen->hasPages())
                <div
                    class="px-6 py-5
                        border-t
                        border-slate-200
                        dark:border-slate-800">

                    {{ $subkomponen->links() }}

                </div>
            @endif

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- MODAL --}}
    {{-- ========================================================= --}}

    <div id="subkomponenModal"
        class="fixed inset-0
           z-50
           hidden
           items-center
           justify-center

           bg-black/50
           dark:bg-black/70

           p-4">


        <div
            class="bg-white
               dark:bg-slate-900

               rounded-3xl

               w-full
               max-w-xl

               shadow-2xl

               border
               border-transparent
               dark:border-slate-800

               max-h-[90vh]
               overflow-y-auto">


            {{-- MODAL HEADER --}}
            <div
                class="flex
                   items-center
                   justify-between

                   px-6 py-5

                   border-b
                   border-slate-200
                   dark:border-slate-800">


                <h2 id="subkomponenModalTitle"
                    class="text-xl
                       font-bold
                       text-slate-900
                       dark:text-white">

                    Tambah Subkomponen

                </h2>


                <button type="button" onclick="closeSubkomponenModal()"
                    class="w-10 h-10
                       rounded-xl

                       text-slate-500
                       dark:text-slate-400

                       hover:bg-slate-100
                       dark:hover:bg-slate-800

                       transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>


            {{-- FORM --}}
            <form id="subkomponenForm" method="POST" action="{{ route('admin.pad.subkomponen.store') }}">

                @csrf

                <div id="subkomponenMethod"></div>


                <div class="p-6 space-y-5">


                    {{-- KOMPONEN --}}
                    <div>

                        <label for="pad_subkomponen_komponen"
                            class="block
                               text-sm
                               font-semibold
                               text-slate-700
                               dark:text-slate-300
                               mb-2">

                            Komponen PAD

                            <span class="text-red-500">*</span>

                        </label>


                        <select name="pad_subkomponen_komponen" id="pad_subkomponen_komponen" required
                            class="w-full
                               rounded-2xl

                               border
                               border-slate-300
                               dark:border-slate-700

                               bg-white
                               dark:bg-slate-800

                               text-slate-900
                               dark:text-white

                               px-4 py-3

                               focus:ring-2
                               focus:ring-blue-500

                               focus:border-blue-500

                               outline-none">

                            <option value="">
                                Pilih Komponen
                            </option>


                            @foreach ($komponen as $item)
                                <option value="{{ $item->pad_komponen_id }}">

                                    {{ $item->pad_komponen_kode ? $item->pad_komponen_kode . ' - ' : '' }}

                                    {{ $item->pad_komponen_nama }}

                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- KODE --}}
                    <div>

                        <label for="pad_subkomponen_kode"
                            class="block
                               text-sm
                               font-semibold
                               text-slate-700
                               dark:text-slate-300
                               mb-2">

                            Kode Subkomponen

                        </label>


                        <input type="text" name="pad_subkomponen_kode" id="pad_subkomponen_kode" placeholder="Contoh: 01"
                            class="w-full
                               rounded-2xl

                               border
                               border-slate-300
                               dark:border-slate-700

                               bg-white
                               dark:bg-slate-800

                               text-slate-900
                               dark:text-white

                               placeholder-slate-400
                               dark:placeholder-slate-500

                               px-4 py-3

                               focus:ring-2
                               focus:ring-blue-500

                               focus:border-blue-500

                               outline-none">

                    </div>


                    {{-- NAMA --}}
                    <div>

                        <label for="pad_subkomponen_nama"
                            class="block
                               text-sm
                               font-semibold
                               text-slate-700
                               dark:text-slate-300
                               mb-2">

                            Nama Subkomponen

                            <span class="text-red-500">*</span>

                        </label>


                        <input type="text" name="pad_subkomponen_nama" id="pad_subkomponen_nama" required
                            placeholder="Contoh: Sewa Gedung"
                            class="w-full
                               rounded-2xl

                               border
                               border-slate-300
                               dark:border-slate-700

                               bg-white
                               dark:bg-slate-800

                               text-slate-900
                               dark:text-white

                               placeholder-slate-400
                               dark:placeholder-slate-500

                               px-4 py-3

                               focus:ring-2
                               focus:ring-blue-500

                               focus:border-blue-500

                               outline-none">

                    </div>


                    {{-- KETERANGAN --}}
                    <div>

                        <label for="pad_subkomponen_keterangan"
                            class="block
                               text-sm
                               font-semibold
                               text-slate-700
                               dark:text-slate-300
                               mb-2">

                            Keterangan

                        </label>


                        <textarea name="pad_subkomponen_keterangan" id="pad_subkomponen_keterangan" rows="3"
                            placeholder="Keterangan tambahan..."
                            class="w-full
                               rounded-2xl

                               border
                               border-slate-300
                               dark:border-slate-700

                               bg-white
                               dark:bg-slate-800

                               text-slate-900
                               dark:text-white

                               placeholder-slate-400
                               dark:placeholder-slate-500

                               px-4 py-3

                               focus:ring-2
                               focus:ring-blue-500

                               focus:border-blue-500

                               outline-none
                               resize-none"></textarea>

                    </div>

                </div>


                {{-- MODAL FOOTER --}}
                <div
                    class="px-6 py-5

                       border-t
                       border-slate-200
                       dark:border-slate-800

                       flex
                       justify-end
                       gap-3">


                    <button type="button" onclick="closeSubkomponenModal()"
                        class="px-5 py-3
                           rounded-2xl

                           bg-slate-100
                           dark:bg-slate-800

                           text-slate-700
                           dark:text-slate-300

                           hover:bg-slate-200
                           dark:hover:bg-slate-700

                           font-semibold

                           transition">

                        Batal

                    </button>


                    <button type="submit"
                        class="px-5 py-3
                           rounded-2xl

                           bg-blue-600
                           hover:bg-blue-700

                           text-white

                           font-semibold

                           transition">

                        <i class="bi bi-check-lg me-1"></i>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>



    <script>
        function openSubkomponenModal() {

            const modal =
                document.getElementById(
                    'subkomponenModal'
                );


            modal.classList.remove('hidden');

            modal.classList.add('flex');


            document.getElementById(
                    'subkomponenModalTitle'
                ).innerText =
                'Tambah Subkomponen';


            document.getElementById(
                    'subkomponenForm'
                ).action =
                "{{ route('admin.pad.subkomponen.store') }}";


            document.getElementById(
                'subkomponenMethod'
            ).innerHTML = '';


            document.getElementById(
                'pad_subkomponen_komponen'
            ).value = '';


            document.getElementById(
                'pad_subkomponen_kode'
            ).value = '';


            document.getElementById(
                'pad_subkomponen_nama'
            ).value = '';


            document.getElementById(
                'pad_subkomponen_keterangan'
            ).value = '';

        }



        function editSubkomponen(item) {

            const modal =
                document.getElementById(
                    'subkomponenModal'
                );


            modal.classList.remove('hidden');

            modal.classList.add('flex');


            document.getElementById(
                    'subkomponenModalTitle'
                ).innerText =
                'Edit Subkomponen';


            document.getElementById(
                    'subkomponenForm'
                ).action =
                `/admin/pad/subkomponen/${item.pad_subkomponen_uid}`;


            document.getElementById(
                    'subkomponenMethod'
                ).innerHTML =
                '@method('PUT')';


            document.getElementById(
                    'pad_subkomponen_komponen'
                ).value =
                item.pad_subkomponen_komponen;


            document.getElementById(
                    'pad_subkomponen_kode'
                ).value =
                item.pad_subkomponen_kode ?? '';


            document.getElementById(
                    'pad_subkomponen_nama'
                ).value =
                item.pad_subkomponen_nama ?? '';


            document.getElementById(
                    'pad_subkomponen_keterangan'
                ).value =
                item.pad_subkomponen_keterangan ?? '';

        }



        function closeSubkomponenModal() {

            const modal =
                document.getElementById(
                    'subkomponenModal'
                );


            modal.classList.add('hidden');

            modal.classList.remove('flex');

        }



        // Tutup ketika klik backdrop
        document
            .getElementById('subkomponenModal')
            .addEventListener('click', function(event) {

                if (event.target === this) {

                    closeSubkomponenModal();

                }

            });


        // ESC
        document.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Escape') {

                    const modal =
                        document.getElementById(
                            'subkomponenModal'
                        );

                    if (
                        modal &&
                        !modal.classList.contains('hidden')
                    ) {

                        closeSubkomponenModal();

                    }

                }

            }
        );
    </script>

@endsection

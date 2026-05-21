<!DOCTYPE html>
<html lang="id">

<head>
    @include('administrator.partials.head')
</head>

<body class="text-white">

    <div class="flex min-h-screen">

        @include('administrator.partials.sidebar')

        <!-- MAIN -->
        <div class="flex-1 flex flex-col">

            <!-- TOPBAR -->
            @include('administrator.partials.header')

            <!-- CONTENT -->
            <main class="p-6 space-y-6">

                <!-- STATS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                        <p class="text-slate-400 text-sm">Total User</p>
                        <h2 class="text-3xl font-bold mt-2">1,240</h2>
                    </div>

                    <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                        <p class="text-slate-400 text-sm">Inventaris</p>
                        <h2 class="text-3xl font-bold mt-2">560</h2>
                    </div>

                    <div class="bg-slate-800 p-5 rounded-2xl shadow-lg border border-slate-700">
                        <p class="text-slate-400 text-sm">Laporan Masuk</p>
                        <h2 class="text-3xl font-bold mt-2">89</h2>
                    </div>

                </div>

                <!-- CHART / TABLE SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- TABLE -->
                    <div class="bg-slate-800 rounded-2xl border border-slate-700 p-5">
                        <h3 class="font-semibold mb-4">User Terbaru</h3>

                        <table class="w-full text-sm">
                            <thead class="text-slate-400">
                                <tr>
                                    <th class="text-left py-2">Nama</th>
                                    <th class="text-left">NIP</th>
                                    <th class="text-left">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr class="border-t border-slate-700">
                                    <td class="py-2">Indra</td>
                                    <td>12345</td>
                                    <td><span class="text-green-400">Aktif</span></td>
                                </tr>

                                <tr class="border-t border-slate-700">
                                    <td class="py-2">Budi</td>
                                    <td>54321</td>
                                    <td><span class="text-red-400">Nonaktif</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- CARD INFO -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 shadow-xl">
                        <h3 class="text-xl font-bold">Selamat Datang 👋</h3>
                        <p class="text-sm mt-2 text-blue-100">
                            Sistem Admin SAPLARIN siap digunakan untuk manajemen data,
                            inventaris, dan laporan internal.
                        </p>

                        <button class="mt-4 bg-white text-blue-600 px-4 py-2 rounded-xl font-semibold">
                            Mulai Kelola
                        </button>
                    </div>

                </div>

            </main>

        </div>
    </div>

    @include('administrator.partials.modal')
</body>

</html>

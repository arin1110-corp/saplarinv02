<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('image/pemprov.png') }}" type="image/png">

    <style>
        body {
            background: #0f172a;
        }
    </style>
</head>

<body class="text-white">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 border-r border-slate-800 hidden md:block">
            <div class="p-6 border-b border-slate-800">

                <!-- LOGO + TITLE -->
                <div class="flex items-center gap-3">

                    <!-- LOGO -->
                    <img src="{{ asset('image/pemprov.png') }}" alt="Logo" class="w-12 h-12 object-contain">

                    <!-- TEXT -->
                    <div>
                        <h1 class="text-2xl font-bold tracking-wide leading-tight">
                            SAPLAR<span class="text-blue-400">IN</span>
                        </h1>

                        <p class="text-xs text-slate-400">
                            Sistem Penataan Laporan Internal
                        </p>
                    </div>

                </div>

                <!-- NAMA DINAS -->
                <div class="mt-4 pt-4 border-t border-slate-800">

                    <p class="text-[11px] uppercase tracking-[2px] text-slate-500 leading-relaxed text-center">
                        Dinas Kebudayaan <br>
                        Provinsi Bali
                    </p>

                </div>

            </div>

            <nav class="mt-6 space-y-2 px-4 text-sm">

                <a href="#" class="block px-4 py-2 rounded-lg bg-blue-600 text-white">
                    Dashboard
                </a>

                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-slate-800">
                    Data User
                </a>

                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-slate-800">
                    Inventaris
                </a>

                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-slate-800">
                    Laporan
                </a>

                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-slate-800">
                    Settings
                </a>
            </nav>
        </aside>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col">

            <!-- TOPBAR -->
            <header class="bg-slate-900 border-b border-slate-800 p-4 flex justify-between items-center">

                <h1 class="text-lg font-semibold">Admin Dashboard</h1>

                <div class="flex items-center gap-4">

                    <!-- BUTTON PROFILE -->
                    <div class="hidden md:block">

                        <button onclick="openRolePopup()"
                            class="bg-slate-800 hover:bg-slate-700 border border-slate-700 px-4 py-2 rounded-xl transition duration-300 text-sm text-slate-200 shadow-lg">

                            {{ session('active_role') }}
                        </button>

                    </div>

                    <img src="https://ui-avatars.com/api/?name=Admin&background=2563eb&color=fff"
                        class="w-10 h-10 rounded-full border border-slate-700">

                </div>
            </header>

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

    <!-- POPUP ROLE -->
    <div class="modal fade" id="roleModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="/set-role">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pilih Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Role yang tersedia</label>

                        <select name="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>

                            @foreach ($roles as $role)
                                <option value="{{ $role }}"
                                    {{ session('active_role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Ganti Role</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#inventarisTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "lengthChange": true,
                "deferRender": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "Belum ada data inventaris"
                }
            });

            $('#perbaikanTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "lengthChange": true,
                "deferRender": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "Tidak ada perbaikan aktif"
                }
            });
        });
    </script>
</body>

</html>

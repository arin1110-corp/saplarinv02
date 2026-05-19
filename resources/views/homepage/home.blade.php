<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPLARIN</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('image/pemprov.png') }}" type="image/png">

    <style>
        body {
            background:
                linear-gradient(rgba(10, 35, 66, 0.88), rgba(10, 35, 66, 0.92));
            background-size: cover;
            background-position: center;
        }

        .glass {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .IN {
            color: #00a2ff;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-5">

    <div class="w-full max-w-5xl rounded-3xl overflow-hidden shadow-2xl glass">

        <div class="grid md:grid-cols-2">

            {{-- LEFT SIDE --}}
            <div class="hidden md:flex flex-col justify-center p-12 text-white relative">

                <div>
                    <h1 class="text-5xl font-bold tracking-wide mb-3">
                        SAPLAR<span class="IN">IN</span>
                    </h1>

                    <div class="w-24 h-1 bg-yellow-400 rounded-full mb-5"></div>

                    <h2 class="text-xl font-semibold mb-4">
                        Sistem Penataan Laporan Internal
                    </h2>

                    <p class="text-slate-200 leading-relaxed">
                        Platform digital pengelolaan laporan internal
                        untuk mendukung tata kelola administrasi yang
                        efektif, transparan, dan terintegrasi pada
                        Dinas Kebudayaan.
                    </p>
                    <!--
                    <div class="mt-10">
                        <p class="text-sm text-slate-300">
                            Dinas Kebudayaan Provinsi Bali
                        </p>
                    </div> -->
                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="bg-white p-8 md:p-12">

                <div class="text-center mb-8">

                    {{-- Logo --}}
                    <img src="{{ asset('image/pemprov.png') }}" alt="Logo" class="w-24 mx-auto mb-4">

                    <h3 class="text-3xl font-bold text-slate-800">
                        SAPLAR<span class="IN">IN</span>
                    </h3>

                </div>
                @if (session('error'))
                    <div
                        class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 shadow-sm">

                        {{-- icon --}}
                        <div class="mt-0.5 text-red-600">
                            ⚠️
                        </div>

                        <div class="text-sm leading-relaxed">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    {{-- Username / NIP --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            NIP / NIK
                        </label>

                        <input type="text" name="nip" value="{{ old('nip') }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:outline-none"
                            placeholder="Masukkan NIP atau NIK">

                        @error('nip')
                            <small class="text-red-500">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Password
                        </label>

                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:outline-none"
                                placeholder="Masukkan Password">

                            <button type="button" onclick="togglePassword()"
                                class="absolute right-4 top-3 text-slate-500">
                                👁
                            </button>
                        </div>

                        @error('password')
                            <small class="text-red-500">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- Button --}}
                    <button type="submit"
                        class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-3 rounded-xl transition duration-300 shadow-lg">

                        Masuk ke Sistem
                    </button>

                </form>

                <div class="mt-8 text-center text-sm text-slate-400">
                    SAPLARIN {{ date('Y') }} v{{ config('app.version') }} • Dinas Kebudayaan
                </div>

            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');

            password.type =
                password.type === 'password' ?
                'text' :
                'password';
        }
    </script>

</body>

</html>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SAPLARIN')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    colors: {
                        primary: '#2563eb',
                        success: '#16a34a',
                        warning: '#d97706',
                        danger: '#dc2626'
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Alpine Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse/dist/cdn.min.js"></script>

    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet"
        href="{{ asset('assets/css/admin-v2.css') }}">

    @stack('styles')

</head>

<body
    x-data="layout()"
    x-init="init()"
    class="bg-slate-100 dark:bg-slate-950 font-sans antialiased">

    {{-- Overlay --}}
    <div
        x-show="sidebarOpen && isMobile"
        x-transition.opacity
        @click="closeSidebar()"
        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        style="display:none">
    </div>

    {{-- Sidebar --}}
    @include('administrator-v2.layouts.sidebar')

    {{-- Content --}}
    <div class="min-h-screen ml-0 lg:ml-72">

        @include('administrator-v2.layouts.header')

        <main class="p-4 lg:p-6">

            @yield('content')

        </main>

        @include('administrator-v2.layouts.footer')

    </div>

    <script src="{{ asset('assets/js/admin-v2.js') }}"></script>

    @stack('scripts')

</body>

</html>

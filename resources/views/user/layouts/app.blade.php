<!DOCTYPE html>
<html lang="id">

<head>
    @include('user.partials.header')
</head>

<body class="bg-[#F5F7FB] text-slate-800">

    @include('user.partials.sidebar')

    <div class="min-h-screen lg:pl-72">

        @include('user.partials.topbar')

        <main class="p-5 lg:p-8">
            @yield('content')
        </main>

        @include('user.partials.footer')

    </div>

    @stack('scripts')

</body>

</html>
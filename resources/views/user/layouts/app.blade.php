<!DOCTYPE html>
<html lang="id">

<head>
    @include('user.partials.header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css">

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
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

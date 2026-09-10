<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Maintenance | SAPLARIN</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .floating {
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .pulse-ring {
            animation: pulse-ring 2.5s ease-out infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(.85);
                opacity: .7;
            }

            70% {
                transform: scale(1.2);
                opacity: 0;
            }

            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

        .gear {
            animation: rotateGear 8s linear infinite;
            transform-origin: center;
        }

        @keyframes rotateGear {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .blob {
            filter: blur(70px);
        }
    </style>
</head>

<body class="min-h-screen overflow-hidden bg-slate-50">

    {{-- BACKGROUND DECORATION --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">

        <div class="blob absolute -top-32 -left-32
                    w-96 h-96
                    bg-blue-400/20 rounded-full">
        </div>

        <div class="blob absolute -bottom-32 -right-32
                    w-96 h-96
                    bg-indigo-500/20 rounded-full">
        </div>

        <div class="absolute inset-0
                    bg-[radial-gradient(circle_at_top_right,_rgba(37,99,235,0.08),_transparent_35%)]">
        </div>

    </div>


    {{-- MAIN --}}
    <div class="relative min-h-screen flex flex-col">

        {{-- HEADER --}}
        <header class="px-6 md:px-10 lg:px-14 py-6">

            <div class="flex items-center gap-3">

                {{-- LOGO --}}
                <div class="w-12 h-12 md:w-14 md:h-14
                            rounded-2xl
                            bg-white
                            border border-slate-200
                            shadow-sm
                            flex items-center justify-center">

                    <div class="w-9 h-9 md:w-10 md:h-10
                                rounded-xl
                                bg-gradient-to-br
                                from-blue-600 to-indigo-700
                                flex items-center justify-center">

                        <i class="bi bi-file-earmark-bar-graph-fill
                                  text-white text-xl md:text-2xl">
                        </i>

                    </div>

                </div>


                {{-- BRAND --}}
                <div>

                    <h1 class="text-xl md:text-2xl
                               font-extrabold
                               tracking-tight
                               text-slate-900">

                        SAPLARIN

                    </h1>

                    <p class="text-xs md:text-sm
                              text-slate-500">

                        Sistem Administrasi Penataan Laporan dan Realisasi Internal

                    </p>

                </div>

            </div>

        </header>


        {{-- CONTENT --}}
        <main class="flex-1 flex items-center justify-center
                     px-6 pb-10">

            <div class="w-full max-w-2xl text-center">


                {{-- ICON --}}
                <div class="flex justify-center mb-8">

                    <div class="relative">

                        {{-- RING --}}
                        <div class="absolute inset-0
                                    rounded-full
                                    border-2
                                    border-blue-400/30
                                    pulse-ring">
                        </div>


                        {{-- ICON CONTAINER --}}
                        <div class="relative
                                    w-28 h-28 md:w-32 md:h-32
                                    rounded-[2rem]
                                    bg-white
                                    border border-slate-200
                                    shadow-xl
                                    shadow-blue-900/10
                                    flex items-center justify-center
                                    floating">

                            <div class="relative
                                        w-20 h-20 md:w-24 md:h-24
                                        rounded-[1.5rem]
                                        bg-gradient-to-br
                                        from-blue-600
                                        via-blue-600
                                        to-indigo-700
                                        flex items-center justify-center">

                                <i class="bi bi-tools
                                          text-white
                                          text-4xl md:text-5xl">
                                </i>


                                {{-- GEAR --}}
                                <div class="absolute
                                            -right-2
                                            -bottom-2
                                            w-9 h-9
                                            rounded-full
                                            bg-white
                                            shadow-lg
                                            flex items-center justify-center">

                                    <i class="bi bi-gear-fill
                                              text-blue-600
                                              text-lg
                                              gear">
                                    </i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- BADGE --}}
                <div class="flex justify-center mb-5">

                    <div class="inline-flex items-center gap-2
                                px-4 py-2
                                rounded-full
                                bg-blue-50
                                border border-blue-100
                                text-blue-700
                                text-sm
                                font-semibold">

                        <span class="relative flex h-2.5 w-2.5">

                            <span class="absolute
                                         inline-flex
                                         h-full w-full
                                         rounded-full
                                         bg-blue-400
                                         opacity-75
                                         animate-ping">
                            </span>

                            <span class="relative
                                         inline-flex
                                         rounded-full
                                         h-2.5 w-2.5
                                         bg-blue-600">
                            </span>

                        </span>

                        Sistem sedang dalam pemeliharaan

                    </div>

                </div>


                {{-- TITLE --}}
                <h2 class="text-3xl md:text-5xl
                           font-extrabold
                           tracking-tight
                           text-slate-900
                           leading-tight">

                    Sedang Dilakukan
                    <span class="block
                                 bg-gradient-to-r
                                 from-blue-600
                                 to-indigo-600
                                 bg-clip-text
                                 text-transparent">

                        Maintenance

                    </span>

                </h2>


                {{-- DESCRIPTION --}}
                <p class="mt-5
                          text-base md:text-lg
                          leading-relaxed
                          text-slate-500
                          max-w-xl
                          mx-auto">

                    SAPLARIN sedang dalam proses pemeliharaan dan
                    penyempurnaan sistem untuk meningkatkan
                    <span class="font-semibold text-slate-700">
                        keamanan, stabilitas, dan kualitas layanan.
                    </span>

                </p>


                {{-- STATUS BOX --}}
                <div class="mt-8
                            bg-white
                            border border-slate-200
                            rounded-3xl
                            shadow-lg
                            shadow-slate-200/50
                            p-5 md:p-6">

                    <div class="flex items-start gap-4 text-left">

                        <div class="flex-shrink-0
                                    w-11 h-11
                                    rounded-2xl
                                    bg-blue-50
                                    flex items-center justify-center">

                            <i class="bi bi-info-circle-fill
                                      text-blue-600
                                      text-xl">
                            </i>

                        </div>


                        <div>

                            <p class="font-bold text-slate-800">

                                Mohon menunggu beberapa saat

                            </p>

                            <p class="text-sm
                                      text-slate-500
                                      mt-1
                                      leading-relaxed">

                                Sistem akan kembali dapat digunakan
                                setelah proses maintenance selesai.
                                Terima kasih atas pengertiannya.

                            </p>

                        </div>

                    </div>

                </div>


                {{-- FOOTER MESSAGE --}}
                <div class="mt-8">

                    <p class="text-sm text-slate-400">

                        Dinas Kebudayaan Provinsi Bali

                    </p>

                    <p class="text-xs text-slate-300 mt-1">

                        Melestarikan Budaya, Merencanakan Masa Depan

                    </p>

                </div>

            </div>

        </main>


        {{-- FOOTER --}}
        <footer class="px-6 md:px-10 lg:px-14 py-5">

            <div class="flex flex-col
                        md:flex-row
                        items-center
                        justify-between
                        gap-2
                        text-xs
                        text-slate-400">

                <span>
                    SAPLARIN
                </span>

                <span>
                    &copy; {{ date('Y') }} Dinas Kebudayaan Provinsi Bali
                </span>

            </div>

        </footer>

    </div>

</body>

</html>
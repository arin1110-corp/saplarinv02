<header
    class="sticky top-0 z-40 h-20 bg-white/90 dark:bg-slate-900/90 backdrop-blur border-b border-slate-200 dark:border-slate-800">

    <div class="h-full px-6 lg:px-8 flex items-center justify-between">

        {{-- Left --}}
        <div class="flex items-center gap-4">

            {{-- Sidebar Toggle --}}
            <button @click="toggleSidebar()"
                class="lg:hidden w-10 h-10 rounded-xl flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                <i class="bi bi-list text-2xl"></i>

            </button>

            <div>

                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">

                    @yield('page-title', 'Dashboard')

                </h1>

                <p class="text-sm text-slate-500 dark:text-slate-400">

                    @yield('page-description', 'Selamat datang di SAPLARIN')

                </p>

            </div>

        </div>

        {{-- Right --}}
        <div class="flex items-center gap-3">

            {{-- Dark Mode --}}
            <button @click="toggleDark()"
                class="w-10 h-10 rounded-xl flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                <i :class="darkMode ? 'bi bi-sun-fill text-yellow-400' : 'bi bi-moon-stars-fill text-slate-700'">

                </i>

            </button>

            {{-- User --}}
            <div x-data="{ open: false }" class="relative">

                <button @click="open=!open"
                    class="flex items-center gap-3 rounded-xl px-2 py-2 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    <div
                        class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

                        A

                    </div>

                    <div class="hidden md:block text-left">

                        <div class="font-semibold text-slate-800 dark:text-white">

                            Administrator

                        </div>

                        <div class="text-xs text-slate-500">

                            Super Admin

                        </div>

                    </div>

                    <i class="bi bi-chevron-down text-xs"></i>

                </button>

                {{-- Dropdown --}}
                <div x-show="open" x-transition @click.away="open=false"
                    class="absolute right-0 mt-3 w-56 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-xl overflow-hidden"
                    style="display:none;">

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800">

                        <i class="bi bi-person"></i>

                        Profil

                    </a>

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-slate-100 dark:hover:bg-slate-800">

                        <i class="bi bi-gear"></i>

                        Pengaturan

                    </a>

                    <hr class="border-slate-200 dark:border-slate-700">

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">

                        <i class="bi bi-box-arrow-right"></i>

                        Logout

                    </a>

                </div>

            </div>

        </div>

    </div>

</header>

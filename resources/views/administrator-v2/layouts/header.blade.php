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

            

        </div>

    </div>

</header>

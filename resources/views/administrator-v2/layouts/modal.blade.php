@php
    $roles = session('roles', []);

    if (is_string($roles)) {
        $roles = [$roles];
    }

    $roles = array_values(array_unique($roles));
@endphp

<div id="roleModal"
    class="fixed inset-0 z-[999] hidden items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">

    <div
        class="relative w-full max-w-md overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-2xl">

        {{-- Header --}}
        <div
            class="bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-600 px-6 py-5 text-white">

            <div class="flex items-center justify-between">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">

                        <i class="bi bi-person-gear text-2xl"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-bold">

                            Ganti Role

                        </h2>

                        <p class="text-xs text-blue-100">

                            Pilih role yang ingin digunakan

                        </p>

                    </div>

                </div>

                <button
                    type="button"
                    onclick="closeRoleModal()"
                    class="flex h-10 w-10 items-center justify-center rounded-xl hover:bg-white/20 transition">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

        </div>

        {{-- Body --}}
        <form method="POST" action="{{ route('set.role') }}">

            @csrf

            <div class="space-y-5 p-6">

                <div>

                    <label
                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                        Role Aktif

                    </label>

                    <select
                        name="role"
                        required
                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-3 text-slate-800 dark:text-white focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <option value="">-- Pilih Role --</option>

                        @foreach ($roles as $role)

                            <option
                                value="{{ $role }}"
                                {{ session('active_role') == $role ? 'selected' : '' }}>

                                {{ $role }}

                            </option>

                        @endforeach

                    </select>

                    <div
                        class="mt-3 flex items-center gap-2 rounded-xl bg-blue-50 dark:bg-slate-800 p-3 text-xs text-slate-600 dark:text-slate-400">

                        <i class="bi bi-info-circle-fill text-blue-600"></i>

                        <span>

                            Role akan menentukan menu yang muncul pada SAPLARIN.

                        </span>

                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div
                class="flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-6 py-4">

                <button
                    type="button"
                    onclick="closeRoleModal()"
                    class="rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                    Batal

                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 font-semibold text-white shadow hover:shadow-lg transition">

                    <i class="bi bi-check-circle me-2"></i>

                    Simpan Role

                </button>

            </div>

        </form>

    </div>

</div>

<script>
    function openRoleModal() {
        const modal = document.getElementById('roleModal');

        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    }

    function closeRoleModal() {
        const modal = document.getElementById('roleModal');

        if (!modal) return;

        modal.classList.remove('flex');
        modal.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');
    }

    document.getElementById('roleModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeRoleModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRoleModal();
        }
    });
</script>
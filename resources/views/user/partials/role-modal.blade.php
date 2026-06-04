@php
    $roles = session('roles', []);

    if (is_string($roles)) {
        $roles = [$roles];
    }
@endphp

<div id="roleModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 mx-4">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Ganti Role
                </h2>
                <p class="text-sm text-slate-500">
                    Pilih role kerja yang ingin digunakan.
                </p>
            </div>

            <button type="button" onclick="closeRoleModal()" class="text-slate-400 hover:text-slate-800 text-xl">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('set.role') }}">
            @csrf

            <div class="space-y-3">

                @forelse ($roles as $role)
                    <label class="block cursor-pointer">
                        <input type="radio"
                            name="role"
                            value="{{ $role }}"
                            class="hidden peer"
                            {{ session('active_role') == $role ? 'checked' : '' }}>

                        <div class="border rounded-2xl p-4 transition peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:bg-slate-50">
                            <div class="font-semibold text-slate-800">
                                {{ $role }}
                            </div>

                            <div class="text-xs text-slate-500">
                                @if (str_starts_with($role, 'Admin'))
                                    Masuk ke tampilan admin.
                                @elseif ($role === 'Pegawai')
                                    Masuk ke tampilan pegawai.
                                @else
                                    Hak akses modul tambahan.
                                @endif
                            </div>
                        </div>
                    </label>
                @empty
                    <div class="border border-red-200 bg-red-50 text-red-700 rounded-2xl p-4 text-sm">
                        Role tidak ditemukan. Silakan logout lalu login ulang.
                    </div>
                @endforelse

            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                    onclick="closeRoleModal()"
                    class="px-5 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                    class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 font-semibold">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openRoleModal() {
        const modal = document.getElementById('roleModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRoleModal() {
        const modal = document.getElementById('roleModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('roleModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeRoleModal();
        }
    });
</script>
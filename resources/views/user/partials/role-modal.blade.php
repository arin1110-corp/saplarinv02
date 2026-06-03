@php
    $baseRoles = session('base_roles', []);

    if (is_string($baseRoles)) {
        $baseRoles = [$baseRoles];
    }
@endphp

<div id="roleModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 mx-4">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Ganti Tampilan
                </h2>
                <p class="text-sm text-slate-500">
                    Pilih tampilan Admin atau Pegawai.
                </p>
            </div>

            <button onclick="closeRoleModal()" class="text-slate-400 hover:text-slate-800 text-xl">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('set.role') }}">
            @csrf

            <div class="space-y-3">

                @foreach ($baseRoles as $role)
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
                                @if ($role === 'Admin')
                                    Masuk ke tampilan admin.
                                @else
                                    Masuk ke tampilan pegawai.
                                @endif
                            </div>
                        </div>
                    </label>
                @endforeach

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
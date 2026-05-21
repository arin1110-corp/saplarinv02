<!-- POPUP ROLE -->
<!-- MODAL ROLE -->
<div id="roleModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4">

        <!-- Header -->
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-lg font-semibold text-white">
                Ganti Role
            </h2>

            <button onclick="closeRoleModal()" class="text-slate-400 hover:text-white text-xl">
                ✕
            </button>
        </div>

        <!-- Form -->
        <form method="POST" action="/set-role">
            @csrf

            <div class="mb-4">
                <label class="block text-sm text-slate-300 mb-2">
                    Pilih Role
                </label>

                <select name="role"
                    class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>

                    <option value="">-- Pilih Role --</option>

                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ session('active_role') == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 mt-6">

                <button type="button" onclick="closeRoleModal()"
                    class="px-4 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 transition">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 transition font-medium">
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

    // Tutup modal saat klik backdrop
    document.getElementById('roleModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRoleModal();
        }
    });
</script>
<script>
    function toggleMasterMenu() {

        const menu =
            document.getElementById('masterMenu');

        const arrow =
            document.getElementById('masterArrow');

        if (menu.classList.contains('max-h-0')) {

            menu.classList.remove('max-h-0');
            menu.classList.add('max-h-40');

            arrow.classList.add('rotate-180');

        } else {

            menu.classList.remove('max-h-40');
            menu.classList.add('max-h-0');

            arrow.classList.remove('rotate-180');
        }
    }
</script>

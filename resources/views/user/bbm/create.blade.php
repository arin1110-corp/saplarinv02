@extends('user.layouts.app')

@section('title', 'Tambah Pengajuan BBM')
@section('page_title', 'Tambah Pengajuan BBM')
@section('breadcrumb', 'Pengajuan BBM / Tambah')

@section('content')

    <div class="max-w-4xl">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">

            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900">
                    Form Pengajuan BBM
                </h2>
                <p class="text-sm text-slate-500">
                    Lengkapi data pengajuan BBM.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('user.bbm.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Pengaju
                        </label>
                        <input type="text" value="{{ session('pegawai_nama') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Bidang
                        </label>
                        <input type="text" value="{{ session('pegawai_bidang') ?? '-' }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3" readonly>
                    </div>

                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Sub Kegiatan
                    </label>

                    <select name="bbm_sub_kegiatan_id"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="">-- Pilih Sub Kegiatan --</option>

                        @foreach ($subKegiatans as $sub)
                            <option value="{{ $sub->sub_kegiatan_id }}"
                                {{ old('bbm_sub_kegiatan_id') == $sub->sub_kegiatan_id ? 'selected' : '' }}>
                                {{ $sub->sub_kegiatan_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Uraian Kegiatan
                    </label>

                    <textarea name="bbm_uraian_kegiatan" rows="5"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>{{ old('bbm_uraian_kegiatan') }}</textarea>
                </div>

                <div class="mb-4">

                    <label class="block text-sm font-medium mb-2">
                        Jumlah BBM (Liter)
                    </label>

                    <input type="number" name="bbm_liter" step="0.01" min="0.01" required
                        class="w-full rounded-xl border px-4 py-3">

                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Upload Surat SPT
                    </label>

                    <input type="file" name="bbm_spt_file"
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 bg-white" required>

                    <p class="text-xs text-slate-500 mt-2">
                        Format: PDF, JPG, PNG, DOC, DOCX. Maksimal 5 MB.
                    </p>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('user.bbm.index') }}"
                        class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Kirim Pengajuan
                    </button>
                </div>

            </form>

        </div>

    </div>

@endsection

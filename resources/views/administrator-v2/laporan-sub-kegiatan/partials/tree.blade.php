<div x-data="{

    unit: null,

    program: null,

    kegiatan: null,

    sub: null

}"
    class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden sticky top-24">

    <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800">

        <h2 class="font-bold text-lg">

            Explorer

        </h2>

        <p class="text-sm text-slate-500 mt-1">

            Struktur Laporan

        </p>

    </div>

    <div class="max-h-[78vh] overflow-y-auto p-3">

        @foreach ($tree as $unit)
            <div class="mb-2">

                <button @click="unit = unit=== {{ $loop->index }} ? null : {{ $loop->index }}"
                    class="w-full flex items-center justify-between rounded-xl px-3 py-3 hover:bg-slate-100 dark:hover:bg-slate-800">

                    <div class="flex items-center gap-2">

                        <i class="bi bi-building text-blue-600"></i>

                        <span class="font-semibold">

                            {{ $unit['unit_nama'] }}

                        </span>

                    </div>

                    <i class="bi" :class="unit === {{ $loop->index }} ? 'bi-chevron-down' : 'bi-chevron-right'">

                    </i>

                </button>

                <div x-show="unit==={{ $loop->index }}" x-collapse class="ml-5 mt-2 space-y-2">
                    @foreach ($unit['programs'] as $program)
                        <div>

                            <button
                                @click="program = program==='p{{ $loop->parent->index }}{{ $loop->index }}' ? null : 'p{{ $loop->parent->index }}{{ $loop->index }}'"
                                class="w-full flex items-center justify-between rounded-lg px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-800">

                                <div class="flex items-center gap-2">

                                    <i class="bi bi-folder-fill text-amber-500"></i>

                                    <span>

                                        {{ $program['program']->program_nama ?? '-' }}

                                    </span>

                                </div>

                                <i class="bi"
                                    :class="program === 'p{{ $loop->parent->index }}{{ $loop->index }}' ? 'bi-chevron-down' :
                                        'bi-chevron-right'">

                                </i>

                            </button>

                            <div x-show="program==='p{{ $loop->parent->index }}{{ $loop->index }}'" x-collapse
                                class="ml-5 mt-2 space-y-2">

                                @foreach ($program['kegiatans'] as $kegiatan)
                                    <div>

                                        <button
                                            @click="kegiatan = kegiatan==='k{{ $loop->parent->parent->index }}{{ $loop->parent->index }}{{ $loop->index }}' ? null : 'k{{ $loop->parent->parent->index }}{{ $loop->parent->index }}{{ $loop->index }}'"
                                            class="w-full flex items-center justify-between rounded-lg px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-800">

                                            <div class="flex items-center gap-2">

                                                <i class="bi bi-diagram-3 text-green-600"></i>

                                                <span>

                                                    {{ $kegiatan['kegiatan']->kegiatan_nama ?? '-' }}

                                                </span>

                                            </div>

                                            <i class="bi"
                                                :class="kegiatan ===
                                                    'k{{ $loop->parent->parent->index }}{{ $loop->parent->index }}{{ $loop->index }}'
                                                    ?
                                                    'bi-chevron-down' : 'bi-chevron-right'">

                                            </i>

                                        </button>

                                        <div x-show="kegiatan==='k{{ $loop->parent->parent->index }}{{ $loop->parent->index }}{{ $loop->index }}'"
                                            x-collapse class="ml-5 mt-2 space-y-2">
                                            @foreach ($kegiatan['subKegiatans'] as $sub)
                                                <div>

                                                    <button
                                                        @click="sub = sub==='s{{ $loop->parent->parent->parent->index }}{{ $loop->parent->parent->index }}{{ $loop->parent->index }}{{ $loop->index }}' ? null : 's{{ $loop->parent->parent->parent->index }}{{ $loop->parent->parent->index }}{{ $loop->parent->index }}{{ $loop->index }}'"
                                                        class="w-full flex items-center justify-between rounded-lg px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-800">

                                                        <div class="flex items-center gap-2">

                                                            <i class="bi bi-folder2-open text-purple-600"></i>

                                                            <span>

                                                                {{ $sub['sub']->sub_kegiatan_nama ?? '-' }}

                                                            </span>

                                                        </div>

                                                        <i class="bi"
                                                            :class="sub ===
                                                                's{{ $loop->parent->parent->parent->index }}{{ $loop->parent->parent->index }}{{ $loop->parent->index }}{{ $loop->index }}'
                                                                ?
                                                                'bi-chevron-down' : 'bi-chevron-right'">

                                                        </i>

                                                    </button>

                                                    <div x-show="sub==='s{{ $loop->parent->parent->parent->index }}{{ $loop->parent->parent->index }}{{ $loop->parent->index }}{{ $loop->index }}'"
                                                        x-collapse class="ml-5 mt-2 space-y-1">

                                                        @foreach ($sub['laporan'] as $lap)
                                                            <button type="button"
                                                                onclick="loadLaporan('{{ $lap->laporan_uid }}',this)"
                                                                class="laporan-item w-full flex items-center gap-2 rounded-lg px-3 py-2 text-left hover:bg-blue-50 dark:hover:bg-slate-800 transition">

                                                                <i class="bi bi-file-earmark-text text-red-500"></i>

                                                                <div>

                                                                    <div class="font-medium">

                                                                        {{ \Carbon\Carbon::create()->month($lap->laporan_bulan)->translatedFormat('F') }}

                                                                        {{ $lap->laporan_tahun }}

                                                                    </div>

                                                                    <div class="text-xs text-slate-500">

                                                                        {{ $lap->laporan_created_by_nama ?? '-' }}

                                                                    </div>

                                                                </div>

                                                            </button>
                                                        @endforeach

                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        @endforeach

    </div>

</div>

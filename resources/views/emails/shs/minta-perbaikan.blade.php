<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Perbaikan Usulan SHS</title>
</head>

<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#334155;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:30px 12px;">
        <tr>
            <td align="center">

                <table role="presentation" width="680" cellpadding="0" cellspacing="0"
                    style="width:100%;max-width:680px;background:#ffffff;border-radius:16px;overflow:hidden;">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background:#0f172a;padding:30px 32px;text-align:center;">
                            <div style="font-size:26px;font-weight:bold;letter-spacing:1px;color:#ffffff;">
                                SAPLARIN
                            </div>
                            <div style="font-size:12px;color:#cbd5e1;margin-top:8px;">
                                Sistem Administrasi dan Pelaporan
                            </div>
                            <div style="height:3px;width:60px;background:#38bdf8;margin:18px auto 0;"></div>
                        </td>
                    </tr>

                    {{-- JUDUL --}}
                    <tr>
                        <td style="padding:30px 32px 10px;">
                            <span
                                style="display:inline-block;background:#fff7ed;color:#c2410c;
                                font-size:11px;font-weight:bold;letter-spacing:.5px;
                                padding:8px 12px;border-radius:20px;">
                                MEMERLUKAN PERBAIKAN
                            </span>

                            <h1 style="font-size:23px;line-height:1.4;color:#0f172a;margin:18px 0 12px;">
                                Usulan SHS Anda Perlu Diperbaiki
                            </h1>

                            <p style="font-size:14px;line-height:1.8;color:#475569;margin:0;">
                                Yth. <strong>{{ $namaOperator ?: 'Operator Pengusul' }}</strong>,
                            </p>

                            <p style="font-size:14px;line-height:1.8;color:#475569;margin:12px 0 0;">
                                Admin telah memberikan catatan perbaikan terhadap usulan
                                Standar Harga Satuan (SHS) yang Anda ajukan. Silakan periksa
                                rincian usulan dan catatan di bawah ini, kemudian lakukan
                                perbaikan melalui aplikasi SAPLARIN.
                            </p>
                        </td>
                    </tr>

                    {{-- IDENTITAS USULAN --}}
                    <tr>
                        <td style="padding:24px 32px 12px;">
                            <h2 style="font-size:16px;color:#0f172a;margin:0 0 14px;">
                                1. Identitas Usulan
                            </h2>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="border:1px solid #e2e8f0;border-radius:10px;">

                                <tr>
                                    <td width="36%"
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#64748b;">
                                        Tahun Anggaran</td>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;font-weight:bold;color:#0f172a;">
                                        {{ $tahun ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#64748b;">
                                        Unit</td>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#0f172a;">
                                        {{ $unit ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#64748b;">
                                        Kode Kelompok</td>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#0f172a;">
                                        {{ $kodeKelompok ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px;font-size:13px;color:#64748b;">Kelompok Barang</td>
                                    <td style="padding:12px;font-size:13px;color:#0f172a;">
                                        {{ $kelompokBarang ?: '-' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- RINCIAN BARANG --}}
                    <tr>
                        <td style="padding:20px 32px 12px;">
                            <h2 style="font-size:16px;color:#0f172a;margin:0 0 14px;">
                                2. Rincian Barang / Jasa
                            </h2>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="border:1px solid #e2e8f0;border-radius:10px;">

                                <tr>
                                    <td width="36%"
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#64748b;">
                                        Nama Barang / Jasa</td>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:14px;font-weight:bold;color:#0f172a;">
                                        {{ $barang ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#64748b;">
                                        Merek</td>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#0f172a;">
                                        {{ $merek ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#64748b;">
                                        Tipe</td>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#0f172a;">
                                        {{ $tipe ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#64748b;">
                                        Spesifikasi</td>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;line-height:1.8;color:#0f172a;white-space:pre-line;word-break:break-word;">
                                        {{ $spesifikasi ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#64748b;">
                                        Satuan</td>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#0f172a;">
                                        {{ $satuan ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#64748b;">
                                        TKDN</td>
                                    <td
                                        style="padding:12px;border-bottom:1px solid #e2e8f0;
                                        font-size:13px;color:#0f172a;">
                                        {{ $tkdn !== null && $tkdn !== '' ? $tkdn . '%' : '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:12px;font-size:13px;color:#64748b;">Harga Usulan</td>
                                    <td style="padding:12px;font-size:16px;font-weight:bold;color:#047857;">
                                        Rp {{ number_format((float) ($harga ?? 0), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- DASAR DAN KETERANGAN --}}
                    <tr>
                        <td style="padding:20px 32px 12px;">
                            <h2 style="font-size:16px;color:#0f172a;margin:0 0 14px;">
                                3. Dasar Usulan dan Keterangan
                            </h2>

                            <div style="border:1px solid #e2e8f0;border-radius:10px;padding:16px;margin-bottom:12px;">
                                <div style="font-size:12px;font-weight:bold;color:#64748b;margin-bottom:8px;">
                                    DASAR USULAN
                                </div>
                                <div
                                    style="font-size:13px;line-height:1.8;color:#334155;
                                    white-space:pre-line;word-break:break-word;">
                                    {{ $dasarUsulan ?: '-' }}
                                </div>
                            </div>

                            <div style="border:1px solid #e2e8f0;border-radius:10px;padding:16px;">
                                <div style="font-size:12px;font-weight:bold;color:#64748b;margin-bottom:8px;">
                                    KETERANGAN
                                </div>
                                <div
                                    style="font-size:13px;line-height:1.8;color:#334155;
                                    white-space:pre-line;word-break:break-word;">
                                    {{ $keterangan ?: '-' }}
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- CATATAN ADMIN --}}
                    <tr>
                        <td style="padding:24px 32px 12px;">
                            <h2 style="font-size:16px;color:#0f172a;margin:0 0 14px;">
                                4. Catatan Perbaikan dari Admin
                            </h2>

                            <div
                                style="background:#fff7ed;border:1px solid #fed7aa;
                                border-left:5px solid #ea580c;border-radius:10px;padding:20px;">

                                <div style="font-size:13px;font-weight:bold;color:#9a3412;margin-bottom:10px;">
                                    HAL YANG PERLU DITINDAKLANJUTI
                                </div>

                                <div
                                    style="font-size:14px;line-height:1.9;color:#7c2d12;
                                    white-space:pre-line;word-break:break-word;">
                                    {{ $catatan }}</div>
                            </div>
                        </td>
                    </tr>

                    {{-- PETUNJUK --}}
                    <tr>
                        <td style="padding:20px 32px 30px;">
                            <div
                                style="background:#eff6ff;border:1px solid #bfdbfe;
                                border-radius:10px;padding:16px;">

                                <div style="font-size:13px;font-weight:bold;color:#1d4ed8;margin-bottom:8px;">
                                    Langkah Selanjutnya
                                </div>

                                <ol
                                    style="font-size:13px;line-height:1.9;color:#1e40af;
                                    padding-left:20px;margin:0;">
                                    <li>Masuk ke aplikasi SAPLARIN.</li>
                                    <li>Buka menu Usulan SHS.</li>
                                    <li>Pilih usulan yang perlu diperbaiki.</li>
                                    <li>Klik Edit, sesuaikan data dengan catatan admin, lalu simpan kembali.</li>
                                </ol>
                            </div>

                            <div style="text-align:center;margin-top:24px;">
                                <a href="{{ $urlAplikasi }}"
                                    style="display:inline-block;background:#2563eb;color:#ffffff;
                                    text-decoration:none;font-size:14px;font-weight:bold;
                                    padding:14px 28px;border-radius:8px;">
                                    Buka Aplikasi SAPLARIN
                                </a>
                            </div>

                            <p style="font-size:12px;line-height:1.8;color:#64748b;text-align:center;margin:20px 0 0;">
                                Jika tombol tidak dapat dibuka, salin alamat berikut ke browser:
                                <br>
                                <a href="{{ $urlAplikasi }}" style="color:#2563eb;word-break:break-all;">
                                    {{ $urlAplikasi }}
                                </a>
                            </p>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td
                            style="background:#f8fafc;border-top:1px solid #e2e8f0;
                            padding:22px 32px;text-align:center;">

                            <p style="font-size:12px;line-height:1.8;color:#64748b;margin:0;">
                                Email ini dikirim secara otomatis oleh sistem SAPLARIN.
                                <br>
                                Mohon tidak membalas email ini.
                            </p>

                            <p style="font-size:11px;color:#94a3b8;margin:12px 0 0;">
                                &copy; {{ date('Y') }} SAPLARIN
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>

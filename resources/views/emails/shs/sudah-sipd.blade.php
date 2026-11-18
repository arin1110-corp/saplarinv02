<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan SHS - Sudah Ada di SIPD</title>
</head>

<body
    style="
    margin:0;
    padding:0;
    background-color:#f3f6fb;
    font-family:Arial, Helvetica, sans-serif;
    color:#263445;
">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f6fb;padding:30px 12px;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0"
                    style="
                       max-width:650px;
                       background:#ffffff;
                       border-radius:14px;
                       overflow:hidden;
                   ">

                    <!-- HEADER -->
                    <tr>
                        <td
                            style="
                        background:#102f5f;
                        padding:28px 30px;
                        color:#ffffff;
                    ">
                            <h1
                                style="
                            margin:0;
                            font-size:25px;
                            letter-spacing:1px;
                        ">
                                SAPLARIN
                            </h1>

                            <p
                                style="
                            margin:8px 0 0;
                            font-size:13px;
                            color:#d6e5ff;
                        ">
                                Sistem Informasi Standar Harga Satuan
                            </p>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding:30px;">

                            <p style="font-size:15px;">
                                Yth. Bapak/Ibu
                                <strong>{{ $namaOperator ?: 'Operator SHS' }}</strong>,
                            </p>

                            <p
                                style="
                            font-size:14px;
                            line-height:1.8;
                            color:#475467;
                        ">
                                Terima kasih telah mengajukan usulan Standar Harga
                                Satuan (SHS) melalui aplikasi SAPLARIN.
                            </p>

                            <p
                                style="
                            font-size:14px;
                            line-height:1.8;
                            color:#475467;
                        ">
                                Berdasarkan hasil pemeriksaan administrator,
                                kami informasikan bahwa usulan SHS yang telah
                                Saudara ajukan dinyatakan:
                            </p>

                            <!-- STATUS HIGHLIGHT -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="
                                   margin:25px 0;
                                   background:#e8f5e9;
                                   border:2px solid #2e9d50;
                                   border-radius:12px;
                               ">
                                <tr>
                                    <td align="center" style="padding:25px 15px;">

                                        <p
                                            style="
                                        margin:0 0 12px;
                                        font-size:12px;
                                        font-weight:bold;
                                        color:#23743b;
                                        letter-spacing:2px;
                                    ">
                                            INFORMASI STATUS USULAN SHS
                                        </p>

                                        <p
                                            style="
                                        margin:0;
                                        font-size:25px;
                                        line-height:1.3;
                                        font-weight:800;
                                        color:#16803c;
                                    ">
                                            &#10003; SUDAH ADA DI SIPD
                                        </p>

                                        <p
                                            style="
                                        margin:12px 0 0;
                                        font-size:13px;
                                        line-height:1.6;
                                        color:#356647;
                                    ">
                                            Usulan Standar Harga Satuan (SHS)
                                            Anda telah tersedia di dalam
                                            Sistem Informasi Pemerintahan Daerah (SIPD).
                                        </p>

                                    </td>
                                </tr>
                            </table>

                            <!-- DETAIL USULAN -->
                            <h3
                                style="
                            margin:28px 0 15px;
                            font-size:16px;
                            color:#102f5f;
                        ">
                                Informasi Usulan SHS
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="
                                   border-collapse:collapse;
                                   font-size:13px;
                               ">

                                <tr>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;width:38%;color:#667085;">
                                        Tahun Anggaran
                                    </td>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;font-weight:bold;">
                                        {{ $tahun }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;color:#667085;">
                                        Unit Kerja
                                    </td>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;font-weight:bold;">
                                        {{ $unit }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;color:#667085;">
                                        Barang/Jasa
                                    </td>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;font-weight:bold;">
                                        {{ $barang }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;color:#667085;">
                                        Merek
                                    </td>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;">
                                        {{ $merek ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;color:#667085;">
                                        Tipe
                                    </td>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;">
                                        {{ $tipe ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;color:#667085;">
                                        Spesifikasi
                                    </td>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;">
                                        {{ $spesifikasi ?: '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;color:#667085;">
                                        Satuan
                                    </td>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;">
                                        {{ $satuan }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px;border-bottom:1px solid #e9edf3;color:#667085;">
                                        Harga Usulan
                                    </td>
                                    <td
                                        style="padding:10px;border-bottom:1px solid #e9edf3;font-weight:bold;color:#102f5f;">
                                        Rp {{ number_format((float) $harga, 0, ',', '.') }}
                                    </td>
                                </tr>

                            </table>

                            <!-- KETERANGAN -->
                            <div
                                style="
                            margin-top:25px;
                            padding:17px;
                            background:#f8f9fc;
                            border-left:4px solid #1677e8;
                            border-radius:5px;
                        ">
                                <p
                                    style="
                                margin:0;
                                font-size:13px;
                                line-height:1.8;
                                color:#475467;
                            ">
                                    Sehubungan dengan hal tersebut, usulan SHS ini
                                    tidak perlu diajukan kembali karena telah
                                    tersedia dalam SIPD. Silakan melakukan
                                    pengecekan pada sistem SIPD sebagai tindak lanjut.
                                </p>
                            </div>

                            <p
                                style="
                            font-size:13px;
                            line-height:1.8;
                            color:#475467;
                            margin-top:25px;
                        ">
                                Apabila terdapat perbedaan spesifikasi atau
                                informasi lainnya, silakan berkoordinasi dengan
                                administrator SAPLARIN.
                            </p>

                            <!-- BUTTON SAPLARIN -->
                            <table cellpadding="0" cellspacing="0" style="margin-top:20px;">
                                <tr>
                                    <td
                                        style="
                                    background:#1677e8;
                                    border-radius:7px;
                                ">
                                        <a href="{{ $urlAplikasi }}"
                                            style="
                                           display:inline-block;
                                           padding:13px 22px;
                                           color:#ffffff;
                                           text-decoration:none;
                                           font-size:13px;
                                           font-weight:bold;
                                       ">
                                            Buka Aplikasi SAPLARIN
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- SAWERIA -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="
                                   margin-top:35px;
                                   background:#fff8eb;
                                   border:1px solid #f5d69b;
                                   border-radius:10px;
                               ">
                                <tr>
                                    <td align="center" style="padding:22px 18px;">

                                        <p
                                            style="
                                        margin:0;
                                        font-size:15px;
                                        font-weight:bold;
                                        color:#8a5700;
                                    ">
                                            Dukung Pengembangan SAPLARIN
                                        </p>

                                        <p
                                            style="
                                        margin:10px 0 17px;
                                        font-size:12px;
                                        line-height:1.7;
                                        color:#856b43;
                                    ">
                                            Jika aplikasi ini memberikan manfaat,
                                            Anda dapat memberikan dukungan sukarela
                                            untuk pengembangan dan pemeliharaan
                                            aplikasi SAPLARIN.
                                        </p>

                                        <a href="{{ $urlSaweria }}"
                                            style="
                                           display:inline-block;
                                           padding:12px 22px;
                                           background:#f59e0b;
                                           color:#ffffff;
                                           text-decoration:none;
                                           font-size:13px;
                                           font-weight:bold;
                                           border-radius:7px;
                                       ">
                                            Dukung melalui Saweria
                                        </a>

                                    </td>
                                </tr>
                            </table>

                            <!-- CLOSING -->
                            <p
                                style="
                            margin-top:28px;
                            font-size:13px;
                            line-height:1.8;
                            color:#475467;
                        ">
                                Terima kasih atas perhatian dan kerja sama Saudara.
                            </p>

                            <p
                                style="
                            font-size:13px;
                            line-height:1.7;
                            color:#475467;
                        ">
                                Hormat kami,<br>
                                <strong>Administrator SAPLARIN</strong><br>
                                Dinas Kebudayaan Provinsi Bali
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td align="center"
                            style="
                        padding:20px;
                        background:#f8f9fc;
                        border-top:1px solid #edf0f5;
                    ">
                            <p
                                style="
                            margin:0;
                            font-size:11px;
                            line-height:1.7;
                            color:#98a2b3;
                        ">
                                Email ini dikirim secara otomatis oleh sistem SAPLARIN.
                                Mohon tidak membalas email ini.
                            </p>

                            <p
                                style="
                            margin:8px 0 0;
                            font-size:11px;
                            color:#98a2b3;
                        ">
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

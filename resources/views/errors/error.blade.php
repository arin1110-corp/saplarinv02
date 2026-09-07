@php
    $errorCode = (string) ($code ?? '500');

    $errors = [
        '403' => [
            'title' => 'Akses Ditolak',
            'description' => 'Anda tidak memiliki izin untuk mengakses halaman ini.',
            'icon' => 'bi-shield-lock',
        ],

        '404' => [
            'title' => 'Halaman Tidak Ditemukan',
            'description' => 'Halaman yang Anda cari tidak tersedia atau mungkin telah dipindahkan.',
            'icon' => 'bi-compass',
        ],

        '419' => [
            'title' => 'Sesi Telah Berakhir',
            'description' => 'Sesi Anda telah berakhir. Silakan kembali dan coba lagi.',
            'icon' => 'bi-clock-history',
        ],

        '429' => [
            'title' => 'Terlalu Banyak Permintaan',
            'description' => 'Terlalu banyak permintaan dalam waktu singkat. Silakan coba beberapa saat lagi.',
            'icon' => 'bi-hourglass-split',
        ],

        '500' => [
            'title' => 'Terjadi Kesalahan',
            'description' => 'Sistem mengalami kendala saat memproses permintaan Anda. Silakan coba lagi.',
            'icon' => 'bi-exclamation-triangle',
        ],

        '503' => [
            'title' => 'Sistem Sedang Dipersiapkan',
            'description' => 'SAMPERIN sedang dalam pemeliharaan. Silakan coba kembali beberapa saat lagi.',
            'icon' => 'bi-tools',
        ],
    ];

    $currentError = $errors[$errorCode] ?? $errors['500'];
@endphp

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $errorCode }} - {{ $currentError['title'] }} | SAMPERIN
    </title>

    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" href="{{ asset('assets/images/logo-samperin.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --samperin-navy: #101b4d;
            --samperin-blue: #1677ff;
            --samperin-blue-dark: #0868eb;
            --samperin-blue-soft: #edf5ff;
            --samperin-orange: #f28c28;
            --samperin-text: #405a82;
            --samperin-border: #dfe8f3;
            --samperin-bg: #f4f9ff;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            margin: 0;

            min-height: 100vh;

            font-family: 'Inter', sans-serif;

            color: var(--samperin-navy);

            background:
                radial-gradient(circle at 8% 12%,
                    rgba(22, 119, 255, .08),
                    transparent 27%),
                radial-gradient(circle at 92% 88%,
                    rgba(242, 140, 40, .08),
                    transparent 27%),
                var(--samperin-bg);
        }

        .samperin-error-page {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px 20px;
        }

        .samperin-error-wrapper {
            width: 100%;
            max-width: 650px;

            text-align: center;
        }

        /* LOGO */

        .samperin-error-logo {
            display: block;

            width: 250px;
            max-width: 75%;
            height: auto;

            margin: 0 auto 28px;

            object-fit: contain;
        }

        /* CARD */

        .samperin-error-card {
            position: relative;

            overflow: hidden;

            background: #fff;

            border: 1px solid var(--samperin-border);

            border-radius: 20px;

            padding: 42px 35px 38px;

            box-shadow:
                0 18px 50px rgba(25, 60, 100, .08);
        }

        .samperin-error-card::before {
            content: '';

            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            height: 4px;

            background: linear-gradient(90deg,
                    var(--samperin-blue),
                    var(--samperin-orange));
        }

        /* ICON */

        .samperin-error-icon {
            width: 66px;
            height: 66px;

            margin: 0 auto 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 18px;

            background: var(--samperin-blue-soft);

            color: var(--samperin-blue);

            font-size: 29px;
        }

        /* CODE */

        .samperin-error-code {
            margin-bottom: 12px;

            color: var(--samperin-blue);

            font-size: 68px;
            line-height: 1;

            font-weight: 800;

            letter-spacing: -4px;
        }

        /* TITLE */

        .samperin-error-title {
            margin: 0;

            color: var(--samperin-navy);

            font-size: 25px;
            line-height: 1.3;

            font-weight: 800;
        }

        /* DESCRIPTION */

        .samperin-error-description {
            max-width: 490px;

            margin: 12px auto 0;

            color: var(--samperin-text);

            font-size: 14px;
            line-height: 1.7;
        }

        /* LINE */

        .samperin-error-line {
            width: 70px;
            height: 3px;

            margin: 21px auto 0;

            border-radius: 10px;

            background: linear-gradient(90deg,
                    var(--samperin-blue),
                    var(--samperin-orange));
        }

        /* BUTTON */

        .samperin-error-actions {
            display: flex;

            align-items: center;
            justify-content: center;

            gap: 10px;

            margin-top: 27px;
        }

        .samperin-error-button {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 8px;

            min-height: 42px;

            padding: 0 18px;

            border: 0;
            border-radius: 9px;

            background: var(--samperin-blue);

            color: #fff;

            font-size: 13px;
            font-weight: 700;

            text-decoration: none;

            transition: .2s ease;
        }

        .samperin-error-button:hover {
            background: var(--samperin-blue-dark);

            color: #fff;

            transform: translateY(-1px);
        }

        .samperin-error-button-secondary {
            background: #fff;

            color: var(--samperin-text);

            border: 1px solid var(--samperin-border);
        }

        .samperin-error-button-secondary:hover {
            background: #f7faff;

            color: var(--samperin-navy);

            border-color: #cbdbea;
        }

        /* FOOTER */

        .samperin-error-footer {
            margin-top: 22px;

            color: #71849e;

            font-size: 11px;

            line-height: 1.6;
        }

        .samperin-error-footer strong {
            color: var(--samperin-navy);

            font-weight: 800;
        }

        /* MOBILE */

        @media (max-width: 600px) {

            .samperin-error-page {
                padding: 20px 14px;
            }

            .samperin-error-logo {
                width: 195px;

                margin-bottom: 22px;
            }

            .samperin-error-card {
                padding: 34px 20px 30px;

                border-radius: 16px;
            }

            .samperin-error-icon {
                width: 58px;
                height: 58px;

                border-radius: 15px;

                font-size: 25px;
            }

            .samperin-error-code {
                font-size: 58px;

                letter-spacing: -3px;
            }

            .samperin-error-title {
                font-size: 21px;
            }

            .samperin-error-description {
                font-size: 13px;
            }

            .samperin-error-actions {
                flex-direction: column;
            }

            .samperin-error-button {
                width: 100%;
            }

            .samperin-error-footer {
                font-size: 10px;
            }
        }
    </style>

</head>

<body>

    <main class="samperin-error-page">

        <div class="samperin-error-wrapper">

            {{-- LOGO SAMPERIN --}}
            <img src="{{ asset('assets/images/logo-samperin-full.png') }}" class="samperin-error-logo" alt="SAMPERIN">

            {{-- ERROR CARD --}}
            <section class="samperin-error-card">

                <div class="samperin-error-icon">
                    <i class="bi {{ $currentError['icon'] }}"></i>
                </div>

                <div class="samperin-error-code">
                    {{ $errorCode }}
                </div>

                <div class="samperin-error-line"></div>

                <h1 class="samperin-error-title">
                    {{ $currentError['title'] }}
                </h1>

                <p class="samperin-error-description">
                    {{ $currentError['description'] }}
                </p>

                <div class="samperin-error-actions">

                    <a href="{{ url('/') }}" class="samperin-error-button">

                        <i class="bi bi-house"></i>

                        Kembali ke Beranda

                    </a>

                    <a href="javascript:history.back()" class="samperin-error-button samperin-error-button-secondary">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>

                </div>

            </section>

            {{-- FOOTER --}}
            <div class="samperin-error-footer">

                <strong>SAMPERIN</strong><br>

                Sistem Manajemen Pegawai dan Berkas Internal<br>

                Dinas Kebudayaan Provinsi Bali

            </div>

        </div>

    </main>

</body>

</html>

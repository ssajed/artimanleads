<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Artiman Leads') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background:
                radial-gradient(circle at 10% 10%, rgba(34,197,94,.14), transparent 30%),
                radial-gradient(circle at 90% 90%, rgba(16,185,129,.10), transparent 30%),
                #f1f3f2;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .login-shell {
            width: 100%;
            max-width: 1180px;
            min-height: 650px;
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            direction: ltr;
            background: #fff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow:
                0 30px 80px rgba(15, 23, 42, .14),
                0 10px 30px rgba(15, 23, 42, .07);
        }

        .brand-panel {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            color: #fff;
            background:
                radial-gradient(circle at 20% 20%, rgba(74,222,128,.35), transparent 25%),
                radial-gradient(circle at 85% 80%, rgba(16,185,129,.25), transparent 30%),
                linear-gradient(145deg, #111827 0%, #1f2937 48%, #14532d 100%);
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            border: 1px solid rgba(134,239,172,.18);
            border-radius: 50%;
            top: -170px;
            left: -170px;
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            width: 520px;
            height: 520px;
            border: 1px solid rgba(134,239,172,.12);
            border-radius: 50%;
            bottom: -280px;
            right: -220px;
        }

        .brand-content {
            direction: rtl;
            text-align: center;
            position: relative;
            z-index: 2;
            max-width: 470px;
        }

        .brand-logo {
            width: 150px;
            max-height: 105px;
            object-fit: contain;
            margin-bottom: 30px;
            filter: brightness(0) invert(1);
        }

        .brand-title {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -1px;
            margin: 0 0 14px;
        }

        .brand-title span {
            color: #4ade80;
        }

        .brand-subtitle {
            font-size: 17px;
            line-height: 2;
            color: #d1d5db;
            margin: 0;
        }

        .brand-line {
            width: 70px;
            height: 4px;
            border-radius: 20px;
            background: #22c55e;
            margin: 25px auto;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border: 1px solid rgba(134,239,172,.2);
            background: rgba(255,255,255,.06);
            backdrop-filter: blur(10px);
            border-radius: 999px;
            color: #bbf7d0;
            font-size: 13px;
        }

        .login-panel {
            direction: rtl;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 55px;
            background: #fff;
        }

        .login-content {
            width: 100%;
            max-width: 390px;
        }

        .mobile-brand {
            display: none;
        }

        .welcome {
            margin-bottom: 35px;
        }

        .welcome h1 {
            margin: 0 0 9px;
            font-size: 30px;
            font-weight: 800;
            color: #111827;
        }

        .welcome p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            margin-bottom: 9px;
            color: #374151;
            font-size: 13px;
            font-weight: 700;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap svg {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #9ca3af;
            pointer-events: none;
        }

        .login-input {
            width: 100%;
            height: 54px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #f9fafb;
            padding: 0 50px 0 45px;
            font-family: inherit;
            font-size: 14px;
            color: #111827;
            outline: none;
            transition: .2s ease;
        }

        .login-input:focus {
            background: #fff;
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34,197,94,.10);
        }

        .password-toggle {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #9ca3af;
            cursor: pointer;
            padding: 5px;
        }

        .password-toggle:hover {
            color: #16a34a;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 5px 0 25px;
            font-size: 12px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #6b7280;
            cursor: pointer;
        }

        .remember input {
            accent-color: #16a34a;
            width: 15px;
            height: 15px;
        }

        .forgot-link {
            color: #16a34a;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            color: #15803d;
        }

        .login-button {
            width: 100%;
            height: 55px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: white;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(22,163,74,.20);
            transition: .2s ease;
        }

        .login-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(22,163,74,.28);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .error-box {
            margin-bottom: 20px;
            padding: 13px 15px;
            border-radius: 12px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 13px;
            line-height: 1.8;
        }

        .footer-text {
            text-align: center;
            margin-top: 28px;
            color: #9ca3af;
            font-size: 11px;
        }

        @media (max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
                max-width: 520px;
                min-height: auto;
            }

            .brand-panel {
                display: none;
            }

            .mobile-brand {
                display: block;
                text-align: center;
                margin-bottom: 30px;
            }

            .mobile-brand img {
                width: 115px;
                max-height: 75px;
                object-fit: contain;
                margin-bottom: 12px;
            }

            .mobile-brand strong {
                display: block;
                color: #111827;
                font-size: 21px;
                font-weight: 800;
            }

            .mobile-brand span {
                color: #16a34a;
            }

            .login-panel {
                padding: 45px 30px;
            }
        }

        @media (max-width: 480px) {
            .login-page {
                padding: 15px;
            }

            .login-shell {
                border-radius: 22px;
            }

            .login-panel {
                padding: 35px 22px;
            }

            .welcome h1 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>
    <main class="login-page">
        <div class="login-shell">

            <section class="brand-panel">
                <div class="brand-content">
                    <img
                        src="{{ asset('images/Artiman-logo.svg') }}"
                        alt="Artiman"
                        class="brand-logo"
                    >

                    <h2 class="brand-title">
                        Artiman <span>Leads</span>
                    </h2>

                    <div class="brand-line"></div>

                    <p class="brand-subtitle">
                        مدیریت هوشمند سرنخ‌ها، مشتریان و فرصت‌های فروش
                        <br>
                        در یک محیط یکپارچه و حرفه‌ای
                    </p>

                    <div class="brand-badge">
                        <span>●</span>
                        سیستم مدیریت ارتباط با مشتری
                    </div>
                </div>
            </section>

            <section class="login-panel">
                <div class="login-content">

                    <div class="mobile-brand">
                        <img
                            src="{{ asset('images/Artiman-logo.svg') }}"
                            alt="Artiman"
                        >
                        <strong>Artiman <span>Leads</span></strong>
                    </div>

                    <div class="welcome">
                        <h1>خوش آمدید 👋</h1>
                        <p>برای ورود به پنل مدیریت، اطلاعات حساب خود را وارد کنید.</p>
                    </div>

                    {{ $slot }}

                    <div class="footer-text">
                        © {{ date('Y') }} Artiman Leads — تمامی حقوق محفوظ است.
                    </div>
                </div>
            </section>

        </div>
    </main>
</body>
</html>

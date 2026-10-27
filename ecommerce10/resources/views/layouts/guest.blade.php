<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#f7f8fc">
        <title>RuangBelanja — @if (request()->routeIs('login')) Masuk @else Daftar @endif</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-page">
        <main class="auth-shell">
            <header class="auth-topbar">
                <a class="brand" href="{{ route('home') }}" aria-label="RuangBelanja beranda">
                    <span class="brand-mark">r.</span>
                    <span>ruang<span>belanja</span></span>
                </a>
                <a class="auth-home-link" href="{{ route('home') }}">Kembali ke beranda <span aria-hidden="true">→</span></a>
            </header>
            <div class="auth-content">
                <section class="auth-intro">
                    <span class="auth-intro-kicker">Belanja lebih mudah ✨</span>
                    <h1>Temukan hal kecil yang bikin harimu spesial.</h1>
                    <p>Masuk atau buat akun untuk menikmati pengalaman belanja yang lebih personal di RuangBelanja.</p>
                    <div class="auth-promo">
                        <span aria-hidden="true">🎁</span>
                        <div><strong>Temukan favoritmu</strong><small>Semua pilihan spesial ada di satu ruang.</small></div>
                    </div>
                </section>
                <section class="auth-card">
                    {{ $slot }}
                </section>
            </div>
            <footer class="auth-footer">© {{ date('Y') }} ruangbelanja. Temukan bahagiamu di sini.</footer>
        </main>
    </body>
</html>

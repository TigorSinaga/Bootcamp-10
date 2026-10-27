<x-guest-layout>
    @php
        /** @var \Illuminate\Support\ViewErrorBag $errors */
    @endphp
    <div class="auth-card-heading">
        <span class="auth-card-kicker">Selamat datang kembali</span>
        <h2>Masuk ke akunmu</h2>
        <p>Lanjutkan pengalaman belanjamu bersama kami.</p>
    </div>

    <x-auth-session-status class="auth-status-message" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="auth-input-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="password" value="Kata sandi" />
            <x-text-input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" />
            <x-input-error :messages="$errors->get('password')" class="auth-input-error" />
        </div>

        <div class="auth-form-options">
            <label class="auth-checkbox" for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a class="auth-text-link" href="{{ route('password.request') }}">Lupa kata sandi?</a>
            @endif
        </div>

        <x-primary-button class="auth-submit">
                Masuk
            </x-primary-button>
    </form>

    <p class="auth-switch">Belum punya akun? <a class="auth-text-link" href="{{ route('register') }}">Daftar sekarang</a></p>
</x-guest-layout>

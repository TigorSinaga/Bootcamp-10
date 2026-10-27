<x-guest-layout>
    @php
        /** @var \Illuminate\Support\ViewErrorBag $errors */
    @endphp
    <div class="auth-card-heading">
        <span class="auth-card-kicker">Mulai perjalanan belanjamu</span>
        <h2>Buat akun baru</h2>
        <p>Daftar untuk menyimpan pilihan dan mendapatkan pengalaman yang lebih personal.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <x-input-label for="name" value="Nama lengkap" />
            <x-text-input id="name" class="auth-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama kamu" />
            <x-input-error :messages="$errors->get('name')" class="auth-input-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="auth-input-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="password" value="Kata sandi" />
            <x-text-input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="Buat kata sandi" />
            <x-input-error :messages="$errors->get('password')" class="auth-input-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="password_confirmation" value="Konfirmasi kata sandi" />
            <x-text-input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="auth-input-error" />
        </div>

        <x-primary-button class="auth-submit">Buat akun</x-primary-button>
    </form>

    <p class="auth-switch">Sudah punya akun? <a class="auth-text-link" href="{{ route('login') }}">Masuk</a></p>
</x-guest-layout>

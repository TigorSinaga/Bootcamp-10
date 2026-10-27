@php
    /** @var \App\Models\User $user */
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp
<section class="profile-form-section">
    <header>
        <h2>Informasi profil</h2>

        <p>Perbarui nama dan alamat email yang terhubung ke akunmu.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="profile-form">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama lengkap" />
            <x-text-input id="name" name="name" type="text" class="profile-input" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="profile-input-error" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" name="email" type="email" class="profile-input" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="profile-input-error" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="profile-verification-note">
                        Email kamu belum diverifikasi.

                        <button form="send-verification" class="profile-inline-button">
                            Kirim ulang email verifikasi
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="profile-success-message">
                            Tautan verifikasi baru telah dikirim ke email kamu.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="profile-form-actions">
            <x-primary-button class="profile-save-button">Simpan perubahan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="profile-success-message"
                >Perubahan tersimpan.</p>
            @endif
        </div>
    </form>
</section>

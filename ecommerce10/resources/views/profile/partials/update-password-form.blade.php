@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp
<section class="profile-form-section">
    <header>
        <h2>Ubah kata sandi</h2>

        <p>Gunakan kata sandi yang kuat untuk menjaga keamanan akunmu.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="profile-form">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Kata sandi saat ini" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="profile-input" autocomplete="current-password" />
            <x-input-error :messages="$errors->getBag('updatePassword')->get('current_password')" class="profile-input-error" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Kata sandi baru" />
            <x-text-input id="update_password_password" name="password" type="password" class="profile-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->getBag('updatePassword')->get('password')" class="profile-input-error" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Konfirmasi kata sandi baru" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="profile-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->getBag('updatePassword')->get('password_confirmation')" class="profile-input-error" />
        </div>

        <div class="profile-form-actions">
            <x-primary-button class="profile-save-button">Perbarui kata sandi</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="profile-success-message"
                >Kata sandi berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>

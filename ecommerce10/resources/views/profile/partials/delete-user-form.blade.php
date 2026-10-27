@php
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp
<section class="profile-form-section">
    <header>
        <h2>Hapus akun</h2>

        <p>Penghapusan akun bersifat permanen. Pastikan kamu sudah menyimpan informasi yang masih dibutuhkan.</p>
    </header>

    <x-danger-button
        class="profile-delete-button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Hapus akun</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->getBag('userDeletion')->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="profile-modal-title">Yakin ingin menghapus akun?</h2>

            <p class="profile-modal-copy">Semua data akun akan dihapus secara permanen. Masukkan kata sandi untuk mengonfirmasi tindakan ini.</p>

            <div class="profile-modal-field">
                <x-input-label for="password" value="Kata sandi" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="profile-input"
                    placeholder="Kata sandi"
                />

                <x-input-error :messages="$errors->getBag('userDeletion')->get('password')" class="profile-input-error" />
            </div>

            <div class="profile-modal-actions">
                <x-secondary-button class="profile-cancel-button" x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-danger-button class="profile-delete-button">
                    Hapus akun
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

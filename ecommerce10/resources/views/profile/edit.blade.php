<x-app-layout>
    <main class="page-content profile-page">
        <nav class="cart-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span aria-hidden="true">/</span>
            <span>Profil</span>
        </nav>
        <div class="profile-heading">
            <span class="cart-eyebrow">Akunmu ✨</span>
            <h1>Pengaturan profil</h1>
            <p>Kelola informasi akun dan keamananmu di sini.</p>
        </div>
        <div class="profile-sections">
            <section class="profile-card">
                @include('profile.partials.update-profile-information-form')
            </section>
            <section class="profile-card">
                @include('profile.partials.update-password-form')
            </section>
            <section class="profile-card profile-danger-card">
                @include('profile.partials.delete-user-form')
            </section>
        </div>
    </main>
</x-app-layout>

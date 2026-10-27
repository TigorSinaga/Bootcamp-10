<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f8fc">
    <title>@yield('title', 'RuangBelanja')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
</head>

<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    @stack('scripts')
    <script>
        (() => {
            const toast = document.querySelector('#toast-message');
            const cartCount = document.querySelector('#cart-count');
            let toastTimeout;

            function showCartMessage(message) {
                if (!toast) return;

                toast.textContent = message;
                toast.classList.add('show');
                window.clearTimeout(toastTimeout);
                toastTimeout = window.setTimeout(() => toast.classList.remove('show'), 2600);
            }

            document.querySelectorAll('.add-to-cart-form').forEach((form) => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const button = form.querySelector('button[type="submit"]');
                    button.disabled = true;

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });
                        const result = await response.json();

                        if (!response.ok) {
                            throw new Error(result.message || 'Produk gagal ditambahkan ke keranjang.');
                        }

                        if (cartCount) cartCount.textContent = result.cartCount;
                        showCartMessage(result.message);
                    } catch (error) {
                        showCartMessage(error.message || 'Terjadi kesalahan saat menambahkan produk.');
                    } finally {
                        button.disabled = false;
                    }
                });
            });
        })();
    </script>
</body>
</html>
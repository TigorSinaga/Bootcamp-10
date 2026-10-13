<?php

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('Belanja berdasarkan kategori')
        ->assertSee('Sweatshirt Essential Cotton')
        ->assertSee('Glow Daily Face Serum');
});

test('the cart page displays its items and order summary', function () {
    $response = $this->get('/cart');

    $response
        ->assertOk()
        ->assertSee('Keranjang belanja')
        ->assertSee('Sweatshirt Essential Cotton')
        ->assertSee('Headphone Wireless SoundPro')
        ->assertSee('Ringkasan pesanan')
        ->assertSee('Rp738.000')
        ->assertSee('Lanjut ke pembayaran');
});

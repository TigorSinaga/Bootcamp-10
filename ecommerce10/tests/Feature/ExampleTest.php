<?php

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the homepage displays products from the database', function () {
    $category = ProductCategory::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);

    Product::create([
        'product_category_id' => $category->id,
        'name' => 'Camera Digital',
        'slug' => 'camera-digital',
        'description' => 'Kamera untuk kebutuhan sehari-hari.',
        'price' => 25000,
        'stock' => 5,
        'image' => 'camera.jpg',
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSee('Belanja berdasarkan kategori')
        ->assertSee('Camera Digital')
        ->assertSee('Electronics');
});

test('the cart starts empty when no products have been added', function () {
    $this->get('/cart')
        ->assertOk()
        ->assertSee('Keranjangmu masih kosong')
        ->assertDontSee('Sweatshirt Essential Cotton')
        ->assertDontSee('Headphone Wireless SoundPro');
});

test('checkout displays current cart details without creating an order', function () {
    $category = ProductCategory::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);
    $product = Product::create([
        'product_category_id' => $category->id,
        'name' => 'Camera Digital',
        'slug' => 'camera-digital',
        'description' => 'Kamera untuk kebutuhan sehari-hari.',
        'price' => 25000,
        'stock' => 5,
        'image' => 'camera.jpg',
    ]);

    $response = $this->withSession(['cart' => [$product->id => 2]])
        ->get(route('checkout'));

    $response->assertOk()
        ->assertSee('Informasi penerima')
        ->assertSee('Metode pembayaran')
        ->assertSee('Camera Digital')
        ->assertSee('2 × Rp25.000')
        ->assertSee('Rp50.000')
        ->assertSee('Rp62.000')
        ->assertSee('Invoice yang dibuat bersifat sementara');
    $this->assertDatabaseCount('orders', 0);
});

test('checkout creates a temporary invoice with a WhatsApp confirmation link', function () {
    $category = ProductCategory::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);
    $product = Product::create([
        'product_category_id' => $category->id,
        'name' => 'Camera Digital',
        'slug' => 'camera-digital',
        'description' => 'Kamera untuk kebutuhan sehari-hari.',
        'price' => 25000,
        'stock' => 5,
        'image' => 'camera.jpg',
    ]);

    $response = $this->withSession(['cart' => [$product->id => 2]])
        ->post(route('checkout.store'), [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'email' => 'budi@example.com',
            'address' => 'Jalan Melati No. 10, Jakarta',
            'delivery_note' => 'Rumah pagar hitam',
            'payment_method' => 'bank_transfer',
        ]);

    $response->assertRedirect(route('invoice'))
        ->assertSessionHas('pending_invoice')
        ->assertSessionHas('cart', []);
    $this->assertDatabaseCount('orders', 0);

    $invoice = session('pending_invoice');
    $this->get(route('invoice'))
        ->assertOk()
        ->assertSee('Checkout berhasil')
        ->assertSee('#'.$invoice['id'])
        ->assertSee('Budi Santoso')
        ->assertSee('Jalan Melati No. 10, Jakarta')
        ->assertSee('Camera Digital')
        ->assertSee('2 × Rp25.000')
        ->assertSee('Rp62.000')
        ->assertSee('https://wa.me/62895380631090')
        ->assertSee(urlencode('Halo, saya ingin mengonfirmasi pesanan dengan nomor pesanan #'.$invoice['id']));
});

test('checkout shows an empty-cart state when no items are in the session', function () {
    $this->get(route('checkout'))
        ->assertOk()
        ->assertSee('Keranjangmu masih kosong')
        ->assertSee('Tambahkan produk terlebih dahulu');
});

test('invoice redirects to checkout when there is no temporary invoice in session', function () {
    $this->get(route('invoice'))
        ->assertRedirect(route('checkout'))
        ->assertSessionHas('error');
});

test('products added to cart appear with updated quantity and can be removed', function () {
    $category = ProductCategory::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);
    $product = Product::create([
        'product_category_id' => $category->id,
        'name' => 'Camera Digital',
        'slug' => 'camera-digital',
        'description' => 'Kamera untuk kebutuhan sehari-hari.',
        'price' => 25000,
        'stock' => 5,
        'image' => 'camera.jpg',
    ]);

    $this->post(route('cart.items.store'), ['product_id' => $product->id])
        ->assertRedirect();
    $this->get('/cart')
        ->assertOk()
        ->assertSee('Camera Digital')
        ->assertSee('1 item')
        ->assertSee('Rp25.000');

    $this->patchJson(route('cart.items.update', $product), ['quantity' => 2])
        ->assertOk()
        ->assertJsonPath('quantity', 2)
        ->assertJsonPath('itemTotal', 50000)
        ->assertJsonPath('subtotal', 50000)
        ->assertJsonPath('shippingFee', 12000)
        ->assertJsonPath('total', 62000);
    $this->get('/cart')
        ->assertOk()
        ->assertSee('2 item')
        ->assertSee('Rp50.000');
    $this->get('/cart')
        ->assertSeeText('Camera Digital')
        ->assertSeeText('2 × Rp25.000')
        ->assertSeeText('Rp50.000');

    $this->delete(route('cart.items.destroy', $product))
        ->assertRedirect();
    $this->get('/cart')
        ->assertOk()
        ->assertSee('Keranjangmu masih kosong')
        ->assertDontSee('Camera Digital');
});

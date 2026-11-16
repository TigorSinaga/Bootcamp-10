<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

test('authenticated customer can checkout and create order in database', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $product = Product::create([
        'name' => 'Produk Test',
        'slug' => 'produk-test',
        'description' => 'Deskripsi test',
        'price' => 50000,
        'stock' => 10,
        'image' => 'test.jpg',
        'is_active' => true,
    ]);

    $this->actingAs($user);

    // Add to cart session
    session(['cart' => [$product->id => 2]]);

    $response = $this->post(route('checkout.store'), [
        'name' => 'Budi Santoso',
        'phone' => '081234567890',
        'email' => 'budi@example.com',
        'address' => 'Jl. Merdeka No. 10, Jakarta',
        'delivery_note' => 'Titik patokan dekat pos satpam',
        'payment_method' => 'bank_transfer',
    ]);

    $response->assertRedirect();

    // Verify order created in database
    $order = Order::where('customer_email', 'budi@example.com')->first();
    expect($order)->not->toBeNull();
    expect((float) $order->total_amount)->toBe(100000.0); // 50k*2 + shipping 0 (>= 100k)
    expect($order->order_items)->toHaveCount(1);
    expect($order->order_items->first()->product_id)->toBe($product->id);
    expect($order->order_items->first()->quantity)->toBe(2);

    // Verify stock decremented
    expect($product->fresh()->stock)->toBe(8);

    // Verify cart cleared
    expect(session('cart', []))->toBeEmpty();
});

test('checkout fails when stock is insufficient', function () {
    $user = User::factory()->create(['role' => 'customer']);
    $product = Product::create([
        'name' => 'Produk Terbatas',
        'slug' => 'produk-terbatas',
        'description' => 'Deskripsi',
        'price' => 50000,
        'stock' => 1,
        'image' => 'test.jpg',
        'is_active' => true,
    ]);

    $this->actingAs($user);
    session(['cart' => [$product->id => 5]]);

    $response = $this->post(route('checkout.store'), [
        'name' => 'Budi Santoso',
        'phone' => '081234567890',
        'email' => 'budi@example.com',
        'address' => 'Jl. Merdeka No. 10, Jakarta',
        'payment_method' => 'bank_transfer',
    ]);

    $response->assertRedirect(route('checkout'));
    expect(Order::count())->toBe(0);
    expect($product->fresh()->stock)->toBe(1);
});

test('admin can view orders list and update status with stock restoration on cancel', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $product = Product::create([
        'name' => 'Produk Admin',
        'slug' => 'produk-admin',
        'description' => 'Deskripsi',
        'price' => 50000,
        'stock' => 8,
        'image' => 'test.jpg',
        'is_active' => true,
    ]);

    $order = Order::create([
        'order_number' => 'ORD-20261117-9999',
        'user_id' => $admin->id,
        'customer_name' => 'Test Customer',
        'customer_email' => 'test@example.com',
        'customer_phone' => '0812345678',
        'shipping_address' => 'Test Address',
        'subtotal' => 100000,
        'shipping_fee' => 0,
        'total_amount' => 100000,
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'payment_method' => 'bank_transfer',
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'price' => 50000,
        'quantity' => 2,
        'subtotal' => 100000,
    ]);

    $this->actingAs($admin);

    // View admin orders index
    $this->get(route('admin.orders.index'))->assertStatus(200);

    // View admin order detail
    $this->get(route('admin.orders.show', $order))->assertStatus(200);

    // Update status to cancelled (should restore stock from 8 to 10)
    $response = $this->patch(route('admin.orders.update-status', $order), [
        'status' => 'cancelled',
        'payment_status' => 'unpaid',
    ]);

    $response->assertRedirect();
    expect($order->fresh()->status)->toBe('cancelled');
    expect($product->fresh()->stock)->toBe(10);
});

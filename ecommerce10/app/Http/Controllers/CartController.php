<?php

namespace App\Http\Controllers;

class CartController extends Controller
{
    public function index()
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Sweatshirt Essential Cotton',
                'category' => 'Fashion',
                'variant' => 'Cream / M',
                'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=300&q=80',
                'price' => 189000,
                'quantity' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Headphone Wireless SoundPro',
                'category' => 'Elektronik',
                'variant' => 'Butter Yellow',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=300&q=80',
                'price' => 549000,
                'quantity' => 1,
            ],
        ];

        $subtotal = array_sum(array_map(
            fn (array $item) => $item['price'] * $item['quantity'],
            $items,
        ));
        $shippingFee = $subtotal >= 100000 ? 0 : 12000;

        return view('cart.index', [
            'items' => $items,
            'cartCount' => array_sum(array_column($items, 'quantity')),
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'total' => $subtotal + $shippingFee,
        ]);
    }
}

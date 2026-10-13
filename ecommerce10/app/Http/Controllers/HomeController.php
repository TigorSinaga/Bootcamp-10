<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = [
            ['slug' => 'fashion', 'name' => 'Fashion', 'icon' => '👕', 'style' => 'cat-fashion', 'product_count' => '120+'],
            ['slug' => 'elektronik', 'name' => 'Elektronik', 'icon' => '🎧', 'style' => 'cat-tech', 'product_count' => '86+'],
            ['slug' => 'rumah', 'name' => 'Rumah & Living', 'icon' => '🪴', 'style' => 'cat-home', 'product_count' => '94+'],
            ['slug' => 'kecantikan', 'name' => 'Kecantikan', 'icon' => '🧴', 'style' => 'cat-beauty', 'product_count' => '68+'],
            ['slug' => 'olahraga', 'name' => 'Olahraga', 'icon' => '🏃', 'style' => 'cat-sports', 'product_count' => '52+'],
            ['slug' => 'makanan', 'name' => 'Makanan', 'icon' => '☕', 'style' => 'cat-food', 'product_count' => '73+'],
        ];

        $products = [
            [
                'category' => 'fashion',
                'category_name' => 'Fashion',
                'name' => 'Sweatshirt Essential Cotton',
                'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=700&q=80',
                'image_alt' => 'Sweatshirt warna netral',
                'badge' => '-30%',
                'rating' => '4.9',
                'review_count' => 128,
                'price' => 189000,
                'original_price' => 269000,
            ],
            [
                'category' => 'elektronik',
                'category_name' => 'Elektronik',
                'name' => 'Headphone Wireless SoundPro',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=700&q=80',
                'image_alt' => 'Headphone wireless warna kuning',
                'badge' => 'Terlaris',
                'rating' => '4.8',
                'review_count' => 96,
                'price' => 549000,
                'original_price' => 699000,
            ],
            [
                'category' => 'rumah',
                'category_name' => 'Rumah & Living',
                'name' => 'Vas Keramik Minimalis',
                'image' => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&w=700&q=80',
                'image_alt' => 'Dekorasi vas keramik minimalis',
                'badge' => '-20%',
                'rating' => '4.7',
                'review_count' => 54,
                'price' => 159000,
                'original_price' => 199000,
            ],
            [
                'category' => 'kecantikan',
                'category_name' => 'Kecantikan',
                'name' => 'Glow Daily Face Serum',
                'image' => 'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?auto=format&fit=crop&w=700&q=80',
                'image_alt' => 'Serum wajah untuk perawatan kulit',
                'badge' => 'Favorit',
                'rating' => '4.9',
                'review_count' => 203,
                'price' => 129000,
                'original_price' => 179000,
            ],
        ];

        return view('home.index', [
            'categories' => $categories,
            'filters' => array_slice($categories, 0, 4),
            'products' => $products,
        ]);
    }
}

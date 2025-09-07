<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Digital products
            [
                'name' => 'E-Book: Laravel Mastery',
                'description' => 'Comprehensive guide to Laravel 11.',
                'type' => 'digital',
                'price' => 3500,
                'image_url' => '/images/ebook1.jpg',
                'download_link' => 'https://example.com/download/ebook1',
                'stock' => 100,
            ],
            [
                'name' => 'Music Pack',
                'description' => 'Royalty-free music tracks.',
                'type' => 'digital',
                'price' => 2000,
                'image_url' => '/images/musicpack.jpg',
                'download_link' => 'https://example.com/download/musicpack',
                'stock' => 100,
            ],
            [
                'name' => 'Photo Bundle',
                'description' => 'High-res stock photos.',
                'type' => 'digital',
                'price' => 1500,
                'image_url' => '/images/photobundle.jpg',
                'download_link' => 'https://example.com/download/photobundle',
                'stock' => 100,
            ],
            [
                'name' => 'Video Course',
                'description' => 'Full-stack web dev course.',
                'type' => 'digital',
                'price' => 5000,
                'image_url' => '/images/videocourse.jpg',
                'download_link' => 'https://example.com/download/videocourse',
                'stock' => 100,
            ],
            [
                'name' => 'Design Templates',
                'description' => 'Editable design files.',
                'type' => 'digital',
                'price' => 2500,
                'image_url' => '/images/templates.jpg',
                'download_link' => 'https://example.com/download/templates',
                'stock' => 100,
            ],
            // Physical products
            [
                'name' => 'T-Shirt',
                'description' => 'Premium cotton t-shirt.',
                'type' => 'physical',
                'price' => 4000,
                'image_url' => '/images/tshirt.jpg',
                'download_link' => null,
                'stock' => 50,
            ],
            [
                'name' => 'Mug',
                'description' => 'Ceramic coffee mug.',
                'type' => 'physical',
                'price' => 2500,
                'image_url' => '/images/mug.jpg',
                'download_link' => null,
                'stock' => 30,
            ],
            [
                'name' => 'Notebook',
                'description' => 'A5 ruled notebook.',
                'type' => 'physical',
                'price' => 1800,
                'image_url' => '/images/notebook.jpg',
                'download_link' => null,
                'stock' => 40,
            ],
            [
                'name' => 'Sticker Pack',
                'description' => 'Vinyl stickers.',
                'type' => 'physical',
                'price' => 1200,
                'image_url' => '/images/stickers.jpg',
                'download_link' => null,
                'stock' => 60,
            ],
            [
                'name' => 'Backpack',
                'description' => 'Durable laptop backpack.',
                'type' => 'physical',
                'price' => 9000,
                'image_url' => '/images/backpack.jpg',
                'download_link' => null,
                'stock' => 20,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

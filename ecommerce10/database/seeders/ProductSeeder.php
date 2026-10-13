<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $product_categories = [
            'Electronics',
            'Fashion',
            'Home & Garden',
            'Sports & Outdoors',
            'Health & Beauty',
            'Toys & Games',
            'Automotive',
            'Books',
            'Music',
            'Movies & TV',
        ];

        foreach ($product_categories as $category) {
            if (DB::table('product_categories')->where('name', $category)->exists()) {
                continue;
            }

            DB::table('product_categories')->insert([
                'name' => $category,
                'slug' => Str::slug($category),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $categoryIds = DB::table('product_categories')
            ->whereIn('name', $product_categories)
            ->pluck('id', 'name')
            ->all();

        $product_of_electronics = [
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Smartphone', 'description' => 'A high-end smartphone with a large display.', 'slug' => 'smartphone', 'price' => 699.99, 'stock' => 50, 'image' => 'smartphone.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Laptop', 'description' => 'A powerful laptop for work and play.', 'slug' => 'laptop', 'price' => 1299.99, 'stock' => 30, 'image' => 'laptop.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Headphones', 'description' => 'Noise-cancelling headphones for immersive sound.', 'slug' => 'headphones', 'price' => 199.99, 'stock' => 100, 'image' => 'headphones.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Smartwatch', 'description' => 'A stylish smartwatch with fitness tracking features.', 'slug' => 'smartwatch', 'price' => 249.99, 'stock' => 75, 'image' => 'smartwatch.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Tablet', 'description' => 'A lightweight tablet for browsing and entertainment.', 'slug' => 'tablet', 'price' => 399.99, 'stock' => 40, 'image' => 'tablet.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Camera', 'description' => 'A digital camera for capturing high-quality photos and videos.', 'slug' => 'camera', 'price' => 499.99, 'stock' => 25, 'image' => 'camera.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Bluetooth Speaker', 'description' => 'A portable Bluetooth speaker for music on the go.', 'slug' => 'bluetooth_speaker', 'price' => 99.99, 'stock' => 150, 'image' => 'bluetooth_speaker.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Gaming Console', 'description' => 'A next-gen gaming console for immersive gaming experiences.', 'slug' => 'gaming_console', 'price' => 499.99, 'stock' => 20, 'image' => 'gaming_console.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Wireless Charger', 'description' => 'A fast wireless charger for compatible devices.', 'slug' => 'wireless_charger', 'price' => 49.99, 'stock' => 200, 'image' => 'wireless_charger.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'VR Headset', 'description' => 'A virtual reality headset for an immersive gaming experience.', 'slug' => 'vr_headset', 'price' => 299.99, 'stock' => 15, 'image' => 'vr_headset.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'External Hard Drive', 'description' => 'A portable external hard drive for data storage.', 'slug' => 'external_hard_drive', 'price' => 89.99, 'stock' => 80, 'image' => 'external_hard_drive.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Wireless Earbuds', 'description' => 'Compact wireless earbuds for on-the-go listening.', 'slug' => 'wireless_earbuds', 'price' => 149.99, 'stock' => 120, 'image' => 'wireless_earbuds.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Smart Home Hub', 'description' => 'A smart home hub for controlling connected devices.', 'slug' => 'smart_home_hub', 'price' => 129.99, 'stock' => 60, 'image' => 'smart_home_hub.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'Action Camera', 'description' => 'A rugged action camera for capturing adventures.', 'slug' => 'action_camera', 'price' => 249.99, 'stock' => 35, 'image' => 'action_camera.jpg'],
            ['category_id' => $categoryIds['Electronics'], 'name' => 'E-Reader', 'description' => 'A lightweight e-reader for reading digital books.', 'slug' => 'e_reader', 'price' => 119.99, 'stock' => 70, 'image' => 'e_reader.jpg'],
        ];

        $product_of_fashion = [
            ['category_id' => $categoryIds['Fashion'], 'name' => 'T-Shirt', 'description' => 'A comfortable cotton t-shirt.', 'slug' => 't_shirt', 'price' => 19.99, 'stock' => 100, 'image' => 't_shirt.jpg'],
            ['category_id' => $categoryIds['Fashion'], 'name' => 'Jeans', 'description' => 'Stylish denim jeans for everyday wear.', 'slug' => 'jeans', 'price' => 49.99, 'stock' => 80, 'image' => 'jeans.jpg'],
            ['category_id' => $categoryIds['Fashion'], 'name' => 'Sneakers', 'description' => 'Trendy sneakers for casual outings.', 'slug' => 'sneakers', 'price' => 79.99, 'stock' => 60, 'image' => 'sneakers.jpg'],
            ['category_id' => $categoryIds['Fashion'], 'name' => 'Jacket', 'description' => 'A warm jacket for cold weather.', 'slug' => 'jacket', 'price' => 99.99, 'stock' => 40, 'image' => 'jacket.jpg'],
            ['category_id' => $categoryIds['Fashion'], 'name' => 'Dress', 'description' => 'An elegant dress for special occasions.', 'slug' => 'dress', 'price' => 89.99, 'stock' => 30, 'image' => 'dress.jpg'],
            ['category_id' => $categoryIds['Fashion'], 'name' => 'Polo Shirt', 'description' => 'A smart casual polo shirt for everyday style.', 'slug' => 'polo_shirt', 'price' => 29.99, 'stock' => 90, 'image' => 'polo_shirt.jpg'],
            ['category_id' => $categoryIds['Fashion'], 'name' => 'Hoodie', 'description' => 'A cozy hoodie perfect for cooler days.', 'slug' => 'hoodie', 'price' => 59.99, 'stock' => 70, 'image' => 'hoodie.jpg'],
            ['category_id' => $categoryIds['Fashion'], 'name' => 'Cargo Pants', 'description' => 'Comfortable cargo pants with multiple pockets.', 'slug' => 'cargo_pants', 'price' => 54.99, 'stock' => 65, 'image' => 'cargo_pants.jpg'],
            ['category_id' => $categoryIds['Fashion'], 'name' => 'Sunglasses', 'description' => 'Stylish sunglasses for sunny days.', 'slug' => 'sunglasses', 'price' => 39.99, 'stock' => 85, 'image' => 'sunglasses.jpg'],
            ['category_id' => $categoryIds['Fashion'], 'name' => 'Sandals', 'description' => 'Lightweight sandals ideal for casual wear.', 'slug' => 'sandals', 'price' => 34.99, 'stock' => 95, 'image' => 'sandals.jpg'],
        ];

        $product_of_homeandgarden = [
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Sofa', 'description' => 'A comfortable sofa for your living room.', 'slug' => 'sofa', 'price' => 499.99, 'stock' => 20, 'image' => 'sofa.jpg'],
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Dining Table', 'description' => 'A stylish dining table for family meals.', 'slug' => 'dining_table', 'price' => 299.99, 'stock' => 15, 'image' => 'dining_table.jpg'],
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Bed Frame', 'description' => 'A sturdy bed frame for a good night\'s sleep.', 'slug' => 'bed_frame', 'price' => 399.99, 'stock' => 10, 'image' => 'bed_frame.jpg'],
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Bookshelf', 'description' => 'A spacious bookshelf for your favorite reads.', 'slug' => 'bookshelf', 'price' => 149.99, 'stock' => 25, 'image' => 'bookshelf.jpg'],
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Coffee Table', 'description' => 'A modern coffee table for your living space.', 'slug' => 'coffee_table', 'price' => 199.99, 'stock' => 30, 'image' => 'coffee_table.jpg'],
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Garden Chair', 'description' => 'A durable garden chair for outdoor relaxation.', 'slug' => 'garden_chair', 'price' => 89.99, 'stock' => 45, 'image' => 'garden_chair.jpg'],
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Lawn Mower', 'description' => 'An efficient lawn mower for a neat garden.', 'slug' => 'lawn_mower', 'price' => 349.99, 'stock' => 12, 'image' => 'lawn_mower.jpg'],
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Ceiling Lamp', 'description' => 'A sleek ceiling lamp to brighten any room.', 'slug' => 'ceiling_lamp', 'price' => 79.99, 'stock' => 50, 'image' => 'ceiling_lamp.jpg'],
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Patio Set', 'description' => 'A stylish patio set for outdoor gatherings.', 'slug' => 'patio_set', 'price' => 599.99, 'stock' => 8, 'image' => 'patio_set.jpg'],
            ['category_id' => $categoryIds['Home & Garden'], 'name' => 'Storage Cabinet', 'description' => 'A practical storage cabinet for organized spaces.', 'slug' => 'storage_cabinet', 'price' => 179.99, 'stock' => 28, 'image' => 'storage_cabinet.jpg'],
        ];

        $product_of_sportsandoutdoors = [
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Treadmill', 'description' => 'A high-quality treadmill for indoor workouts.', 'slug' => 'treadmill', 'price' => 799.99, 'stock' => 10, 'image' => 'treadmill.jpg'],
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Dumbbell Set', 'description' => 'A versatile dumbbell set for strength training.', 'slug' => 'dumbbell_set', 'price' => 149.99, 'stock' => 25, 'image' => 'dumbbell_set.jpg'],
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Yoga Mat', 'description' => 'A comfortable yoga mat for your practice.', 'slug' => 'yoga_mat', 'price' => 29.99, 'stock' => 50, 'image' => 'yoga_mat.jpg'],
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Bicycle', 'description' => 'A durable bicycle for outdoor adventures.', 'slug' => 'bicycle', 'price' => 399.99, 'stock' => 15, 'image' => 'bicycle.jpg'],
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Camping Tent', 'description' => 'A spacious camping tent for outdoor trips.', 'slug' => 'camping_tent', 'price' => 199.99, 'stock' => 20, 'image' => 'camping_tent.jpg'],
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Resistance Bands', 'description' => 'Flexible resistance bands for home workouts.', 'slug' => 'resistance_bands', 'price' => 24.99, 'stock' => 60, 'image' => 'resistance_bands.jpg'],
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Foam Roller', 'description' => 'A foam roller for muscle recovery and stretching.', 'slug' => 'foam_roller', 'price' => 19.99, 'stock' => 55, 'image' => 'foam_roller.jpg'],
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Hiking Backpack', 'description' => 'A durable hiking backpack for outdoor adventures.', 'slug' => 'hiking_backpack', 'price' => 89.99, 'stock' => 35, 'image' => 'hiking_backpack.jpg'],
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Cycling Helmet', 'description' => 'A protective cycling helmet for safety.', 'slug' => 'cycling_helmet', 'price' => 49.99, 'stock' => 40, 'image' => 'cycling_helmet.jpg'],
            ['category_id' => $categoryIds['Sports & Outdoors'], 'name' => 'Fitness Tracker', 'description' => 'A fitness tracker to monitor activity and health.', 'slug' => 'fitness_tracker', 'price' => 99.99, 'stock' => 65, 'image' => 'fitness_tracker.jpg'],
        ];

        $product_of_healthandbeauty = [
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Vitamin C Serum', 'description' => 'Brightening serum for a healthy, glowing complexion.', 'slug' => 'vitamin_c_serum', 'price' => 29.99, 'stock' => 80, 'image' => 'vitamin_c_serum.jpg'],
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Facial Cleanser', 'description' => 'Gentle facial cleanser for daily skincare.', 'slug' => 'facial_cleanser', 'price' => 18.99, 'stock' => 110, 'image' => 'facial_cleanser.jpg'],
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Moisturizer', 'description' => 'Hydrating moisturizer that keeps skin soft and smooth.', 'slug' => 'moisturizer', 'price' => 24.99, 'stock' => 90, 'image' => 'moisturizer.jpg'],
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Shampoo', 'description' => 'Nourishing shampoo for healthy and shiny hair.', 'slug' => 'shampoo', 'price' => 16.99, 'stock' => 120, 'image' => 'shampoo.jpg'],
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Electric Toothbrush', 'description' => 'Powerful electric toothbrush for superior cleaning.', 'slug' => 'electric_toothbrush', 'price' => 39.99, 'stock' => 70, 'image' => 'electric_toothbrush.jpg'],
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Makeup Brush Set', 'description' => 'Professional makeup brush set for precise application.', 'slug' => 'makeup_brush_set', 'price' => 34.99, 'stock' => 60, 'image' => 'makeup_brush_set.jpg'],
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Body Lotion', 'description' => 'Soothing body lotion to keep skin hydrated all day.', 'slug' => 'body_lotion', 'price' => 21.99, 'stock' => 100, 'image' => 'body_lotion.jpg'],
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Hair Dryer', 'description' => 'Fast-drying hair dryer with multiple settings.', 'slug' => 'hair_dryer', 'price' => 49.99, 'stock' => 55, 'image' => 'hair_dryer.jpg'],
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Sunscreen SPF 50', 'description' => 'High-protection sunscreen for outdoor days.', 'slug' => 'sunscreen_spf_50', 'price' => 27.99, 'stock' => 85, 'image' => 'sunscreen_spf_50.jpg'],
            ['category_id' => $categoryIds['Health & Beauty'], 'name' => 'Essential Oil Diffuser', 'description' => 'Aromatic diffuser for relaxation and wellness.', 'slug' => 'essential_oil_diffuser', 'price' => 44.99, 'stock' => 45, 'image' => 'essential_oil_diffuser.jpg'],
        ];

        $product_of_toysandgames = [
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Building Blocks Set', 'description' => 'Creative building blocks set for imaginative play.', 'slug' => 'building_blocks_set', 'price' => 29.99, 'stock' => 95, 'image' => 'building_blocks_set.jpg'],
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Puzzle Board Game', 'description' => 'Family puzzle game that encourages strategic thinking.', 'slug' => 'puzzle_board_game', 'price' => 24.99, 'stock' => 80, 'image' => 'puzzle_board_game.jpg'],
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Remote Control Car', 'description' => 'High-speed remote control car for fun racing.', 'slug' => 'remote_control_car', 'price' => 54.99, 'stock' => 65, 'image' => 'remote_control_car.jpg'],
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Plush Teddy Bear', 'description' => 'A cuddly plush bear for bedtime comfort.', 'slug' => 'plush_teddy_bear', 'price' => 19.99, 'stock' => 120, 'image' => 'plush_teddy_bear.jpg'],
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Play Tent', 'description' => 'A fun play tent for indoor adventures.', 'slug' => 'play_tent', 'price' => 59.99, 'stock' => 40, 'image' => 'play_tent.jpg'],
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Art & Craft Kit', 'description' => 'Colorful craft kit for creative activities.', 'slug' => 'art_and_craft_kit', 'price' => 32.99, 'stock' => 75, 'image' => 'art_and_craft_kit.jpg'],
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Mini Basketball Hoop', 'description' => 'Portable hoop for indoor and outdoor fun.', 'slug' => 'mini_basketball_hoop', 'price' => 39.99, 'stock' => 50, 'image' => 'mini_basketball_hoop.jpg'],
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Wooden Train Set', 'description' => 'Classic wooden train set for little engineers.', 'slug' => 'wooden_train_set', 'price' => 46.99, 'stock' => 70, 'image' => 'wooden_train_set.jpg'],
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Kids Telescope', 'description' => 'Beginner-friendly telescope for star exploration.', 'slug' => 'kids_telescope', 'price' => 57.99, 'stock' => 35, 'image' => 'kids_telescope.jpg'],
            ['category_id' => $categoryIds['Toys & Games'], 'name' => 'Foam Dart Blaster', 'description' => 'Safe foam dart blaster for active playtime.', 'slug' => 'foam_dart_blaster', 'price' => 42.99, 'stock' => 45, 'image' => 'foam_dart_blaster.jpg'],
        ];

        $product_of_automotive = [
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Car Battery', 'description' => 'Reliable car battery for dependable starts.', 'slug' => 'car_battery', 'price' => 129.99, 'stock' => 30, 'image' => 'car_battery.jpg'],
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Tire Inflator', 'description' => 'Portable tire inflator for quick roadside readiness.', 'slug' => 'tire_inflator', 'price' => 49.99, 'stock' => 55, 'image' => 'tire_inflator.jpg'],
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Car Wash Kit', 'description' => 'Complete car wash kit for a polished finish.', 'slug' => 'car_wash_kit', 'price' => 34.99, 'stock' => 85, 'image' => 'car_wash_kit.jpg'],
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Dashboard Camera', 'description' => 'Compact dashboard camera for road safety.', 'slug' => 'dashboard_camera', 'price' => 89.99, 'stock' => 40, 'image' => 'dashboard_camera.jpg'],
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Floor Mats', 'description' => 'Protective floor mats for a cleaner cabin.', 'slug' => 'floor_mats', 'price' => 39.99, 'stock' => 70, 'image' => 'floor_mats.jpg'],
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Car Seat Organizer', 'description' => 'Convenient organizer to keep essentials in reach.', 'slug' => 'car_seat_organizer', 'price' => 24.99, 'stock' => 90, 'image' => 'car_seat_organizer.jpg'],
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Windshield Wiper Set', 'description' => 'High-quality wiper set for clear visibility.', 'slug' => 'windshield_wiper_set', 'price' => 29.99, 'stock' => 75, 'image' => 'windshield_wiper_set.jpg'],
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Portable Jump Starter', 'description' => 'Compact charger for emergency vehicle starts.', 'slug' => 'portable_jump_starter', 'price' => 79.99, 'stock' => 35, 'image' => 'portable_jump_starter.jpg'],
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Parking Sensor', 'description' => 'Reliable parking sensor for safer parking.', 'slug' => 'parking_sensor', 'price' => 44.99, 'stock' => 60, 'image' => 'parking_sensor.jpg'],
            ['category_id' => $categoryIds['Automotive'], 'name' => 'Car Wax Kit', 'description' => 'Gloss-enhancing wax kit for a shiny finish.', 'slug' => 'car_wax_kit', 'price' => 31.99, 'stock' => 80, 'image' => 'car_wax_kit.jpg'],
        ];

        $product_of_books = [
            ['category_id' => $categoryIds['Books'], 'name' => 'Fiction Novel', 'description' => 'A captivating fiction novel for weekend reading.', 'slug' => 'fiction_novel', 'price' => 14.99, 'stock' => 100, 'image' => 'fiction_novel.jpg'],
            ['category_id' => $categoryIds['Books'], 'name' => 'Business Strategy Book', 'description' => 'Actionable insights for growing your business.', 'slug' => 'business_strategy_book', 'price' => 19.99, 'stock' => 80, 'image' => 'business_strategy_book.jpg'],
            ['category_id' => $categoryIds['Books'], 'name' => 'Cookbook', 'description' => 'A collection of delicious recipes for every day.', 'slug' => 'cookbook', 'price' => 22.99, 'stock' => 75, 'image' => 'cookbook.jpg'],
            ['category_id' => $categoryIds['Books'], 'name' => 'Programming Handbook', 'description' => 'A practical handbook for modern developers.', 'slug' => 'programming_handbook', 'price' => 29.99, 'stock' => 60, 'image' => 'programming_handbook.jpg'],
            ['category_id' => $categoryIds['Books'], 'name' => 'Self Help Journal', 'description' => 'A guided journal for personal growth and reflection.', 'slug' => 'self_help_journal', 'price' => 16.99, 'stock' => 90, 'image' => 'self_help_journal.jpg'],
            ['category_id' => $categoryIds['Books'], 'name' => 'Children Story Book', 'description' => 'A charming bedtime story for children.', 'slug' => 'children_story_book', 'price' => 12.99, 'stock' => 130, 'image' => 'children_story_book.jpg'],
            ['category_id' => $categoryIds['Books'], 'name' => 'Science Encyclopedia', 'description' => 'An engaging encyclopedia of science facts.', 'slug' => 'science_encyclopedia', 'price' => 31.99, 'stock' => 45, 'image' => 'science_encyclopedia.jpg'],
            ['category_id' => $categoryIds['Books'], 'name' => 'History Paperback', 'description' => 'A concise history paperback with rich detail.', 'slug' => 'history_paperback', 'price' => 17.99, 'stock' => 70, 'image' => 'history_paperback.jpg'],
            ['category_id' => $categoryIds['Books'], 'name' => 'Design Inspiration Book', 'description' => 'Creative ideas for design and creativity.', 'slug' => 'design_inspiration_book', 'price' => 26.99, 'stock' => 50, 'image' => 'design_inspiration_book.jpg'],
            ['category_id' => $categoryIds['Books'], 'name' => 'Travel Guide', 'description' => 'A practical guide for memorable travel plans.', 'slug' => 'travel_guide', 'price' => 18.99, 'stock' => 85, 'image' => 'travel_guide.jpg'],
        ];

        $product_of_music = [
            ['category_id' => $categoryIds['Music'], 'name' => 'Acoustic Guitar', 'description' => 'Warm and resonant acoustic guitar for all skill levels.', 'slug' => 'acoustic_guitar', 'price' => 299.99, 'stock' => 20, 'image' => 'acoustic_guitar.jpg'],
            ['category_id' => $categoryIds['Music'], 'name' => 'Studio Headphones', 'description' => 'Professional headphones for immersive listening.', 'slug' => 'studio_headphones', 'price' => 149.99, 'stock' => 40, 'image' => 'studio_headphones.jpg'],
            ['category_id' => $categoryIds['Music'], 'name' => 'Bluetooth Speaker', 'description' => 'Portable Bluetooth speaker for music anywhere.', 'slug' => 'music_bluetooth_speaker', 'price' => 79.99, 'stock' => 55, 'image' => 'music_bluetooth_speaker.jpg'],
            ['category_id' => $categoryIds['Music'], 'name' => 'Vinyl Record Player', 'description' => 'Classic record player for analog sound lovers.', 'slug' => 'vinyl_record_player', 'price' => 199.99, 'stock' => 25, 'image' => 'vinyl_record_player.jpg'],
            ['category_id' => $categoryIds['Music'], 'name' => 'Keyboard Piano', 'description' => 'Compact keyboard piano for practice and performance.', 'slug' => 'keyboard_piano', 'price' => 349.99, 'stock' => 15, 'image' => 'keyboard_piano.jpg'],
            ['category_id' => $categoryIds['Music'], 'name' => 'Drum Kit', 'description' => 'Complete drum kit for rhythm and practice.', 'slug' => 'drum_kit', 'price' => 499.99, 'stock' => 12, 'image' => 'drum_kit.jpg'],
            ['category_id' => $categoryIds['Music'], 'name' => 'Ukulele', 'description' => 'Lightweight ukulele for relaxing tunes.', 'slug' => 'ukulele', 'price' => 99.99, 'stock' => 50, 'image' => 'ukulele.jpg'],
            ['category_id' => $categoryIds['Music'], 'name' => 'Portable Microphone', 'description' => 'Compact microphone for singing and streaming.', 'slug' => 'portable_microphone', 'price' => 59.99, 'stock' => 60, 'image' => 'portable_microphone.jpg'],
            ['category_id' => $categoryIds['Music'], 'name' => 'DJ Controller', 'description' => 'Feature-packed DJ controller for mixing sessions.', 'slug' => 'dj_controller', 'price' => 249.99, 'stock' => 18, 'image' => 'dj_controller.jpg'],
            ['category_id' => $categoryIds['Music'], 'name' => 'Music Stand', 'description' => 'Adjustable stand for sheet music and notes.', 'slug' => 'music_stand', 'price' => 24.99, 'stock' => 70, 'image' => 'music_stand.jpg'],
        ];

        $product_of_moviesandtv = [
            ['category_id' => $categoryIds['Movies & TV'], 'name' => '4K Smart TV', 'description' => 'Ultra-clear 4K smart TV for streaming and gaming.', 'slug' => 'smart_tv_4k', 'price' => 699.99, 'stock' => 18, 'image' => 'smart_tv_4k.jpg'],
            ['category_id' => $categoryIds['Movies & TV'], 'name' => 'Streaming Stick', 'description' => 'Small streaming device for your favorite shows.', 'slug' => 'streaming_stick', 'price' => 39.99, 'stock' => 80, 'image' => 'streaming_stick.jpg'],
            ['category_id' => $categoryIds['Movies & TV'], 'name' => 'Blu-ray Player', 'description' => 'A high-quality player for digital and disc viewing.', 'slug' => 'blu_ray_player', 'price' => 89.99, 'stock' => 45, 'image' => 'blu_ray_player.jpg'],
            ['category_id' => $categoryIds['Movies & TV'], 'name' => 'Movie Poster Frame', 'description' => 'Elegant frame for your favorite movie posters.', 'slug' => 'movie_poster_frame', 'price' => 26.99, 'stock' => 90, 'image' => 'movie_poster_frame.jpg'],
            ['category_id' => $categoryIds['Movies & TV'], 'name' => 'Home Theater Speaker', 'description' => 'Immersive home theater speaker for cinematic sound.', 'slug' => 'home_theater_speaker', 'price' => 179.99, 'stock' => 30, 'image' => 'home_theater_speaker.jpg'],
            ['category_id' => $categoryIds['Movies & TV'], 'name' => 'TV Wall Mount', 'description' => 'Sturdy wall mount for a clean viewing setup.', 'slug' => 'tv_wall_mount', 'price' => 49.99, 'stock' => 60, 'image' => 'tv_wall_mount.jpg'],
            ['category_id' => $categoryIds['Movies & TV'], 'name' => 'Soundbar', 'description' => 'Compact soundbar for richer TV audio.', 'slug' => 'soundbar', 'price' => 119.99, 'stock' => 35, 'image' => 'soundbar.jpg'],
            ['category_id' => $categoryIds['Movies & TV'], 'name' => 'Documentary Collection', 'description' => 'A curated collection of world-class documentaries.', 'slug' => 'documentary_collection', 'price' => 32.99, 'stock' => 70, 'image' => 'documentary_collection.jpg'],
            ['category_id' => $categoryIds['Movies & TV'], 'name' => 'Family Movie Night Kit', 'description' => 'Everything needed for a cozy movie night.', 'slug' => 'family_movie_night_kit', 'price' => 44.99, 'stock' => 50, 'image' => 'family_movie_night_kit.jpg'],
            ['category_id' => $categoryIds['Movies & TV'], 'name' => 'Gaming Streaming Webcam', 'description' => 'High-definition webcam for streaming and calls.', 'slug' => 'gaming_streaming_webcam', 'price' => 74.99, 'stock' => 42, 'image' => 'gaming_streaming_webcam.jpg'],
        ];

        $products = [
            ...$product_of_electronics,
            ...$product_of_fashion,
            ...$product_of_homeandgarden,
            ...$product_of_sportsandoutdoors,
            ...$product_of_healthandbeauty,
            ...$product_of_toysandgames,
            ...$product_of_automotive,
            ...$product_of_books,
            ...$product_of_music,
            ...$product_of_moviesandtv,
        ];

        $usdToIdr = 15000;

        foreach ($products as $product) {
            if (DB::table('products')->where('slug', $product['slug'])->exists()) {
                continue;
            }

            DB::table('products')->insert([
                'product_category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'description' => $product['description'],
                'price' => (int) round($product['price'] * $usdToIdr),
                'stock' => $product['stock'],
                'image' => $product['image'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

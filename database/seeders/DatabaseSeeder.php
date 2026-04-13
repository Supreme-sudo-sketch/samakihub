<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\Question;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Users ─────────────────────────────────────────────────────────

        $admin = User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@samakihub.co.ke',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'status'            => 'active',
            'email_verified_at' => now(),
        ]);

        $producer1 = User::create([
            'name'              => 'Juma Mwangi',
            'email'             => 'juma@samakihub.co.ke',
            'password'          => Hash::make('password'),
            'role'              => 'producer',
            'status'            => 'active',
            'phone'             => '+254712345678',
            'location'          => 'Kisumu, Kenya',
            'email_verified_at' => now(),
        ]);

        $producer2 = User::create([
            'name'              => 'Amina Odhiambo',
            'email'             => 'amina@samakihub.co.ke',
            'password'          => Hash::make('password'),
            'role'              => 'producer',
            'status'            => 'active',
            'phone'             => '+254723456789',
            'location'          => 'Mombasa, Kenya',
            'email_verified_at' => now(),
        ]);

        $consumer1 = User::create([
            'name'              => 'Brian Otieno',
            'email'             => 'brian@samakihub.co.ke',
            'password'          => Hash::make('password'),
            'role'              => 'consumer',
            'status'            => 'active',
            'phone'             => '+254734567890',
            'location'          => 'Nairobi, Kenya',
            'email_verified_at' => now(),
        ]);

        $consumer2 = User::create([
            'name'              => 'Grace Wanjiku',
            'email'             => 'grace@samakihub.co.ke',
            'password'          => Hash::make('password'),
            'role'              => 'consumer',
            'status'            => 'active',
            'phone'             => '+254745678901',
            'location'          => 'Nakuru, Kenya',
            'email_verified_at' => now(),
        ]);

        $delivery1 = User::create([
            'name'              => 'Peter Kamau',
            'email'             => 'peter@samakihub.co.ke',
            'password'          => Hash::make('password'),
            'role'              => 'delivery',
            'status'            => 'active',
            'phone'             => '+254756789012',
            'location'          => 'Nairobi, Kenya',
            'email_verified_at' => now(),
        ]);

        // Pending users (awaiting admin approval)
        User::create([
            'name'     => 'Samuel Kipchoge',
            'email'    => 'samuel@example.com',
            'password' => Hash::make('password'),
            'role'     => 'producer',
            'status'   => 'pending',
            'phone'    => '+254767890123',
            'location' => 'Eldoret, Kenya',
        ]);

        User::create([
            'name'     => 'Fatuma Hassan',
            'email'    => 'fatuma@example.com',
            'password' => Hash::make('password'),
            'role'     => 'delivery',
            'status'   => 'pending',
            'phone'    => '+254778901234',
            'location' => 'Mombasa, Kenya',
        ]);

        // ── 2. Products ───────────────────────────────────────────────────────

        $products = [
            [
                'user_id'     => $producer1->id,
                'name'        => 'Tilapia (Fresh)',
                'description' => 'Freshly caught Nile tilapia from Lake Victoria. Cleaned and ready for cooking.',
                'category'    => 'freshwater',
                'freshness'   => 'super_fresh',
                'price_per_kg'=> 350.00,
                'stock_kg'    => 45.5,
                'is_available'=> true,
                'catch_date'  => Carbon::today()->subDay(),
            ],
            [
                'user_id'     => $producer1->id,
                'name'        => 'Omena (Dagaa)',
                'description' => 'Sun-dried silver cyprinid (omena/dagaa) from Lake Victoria. High protein, great for frying.',
                'category'    => 'dried',
                'freshness'   => 'dried',
                'price_per_kg'=> 180.00,
                'stock_kg'    => 100.0,
                'is_available'=> true,
                'catch_date'  => Carbon::today()->subDays(3),
            ],
            [
                'user_id'     => $producer1->id,
                'name'        => 'Nile Perch Fillet',
                'description' => 'Boneless Nile perch fillets. Excellent for grilling or baking. Vacuum-sealed for freshness.',
                'category'    => 'freshwater',
                'freshness'   => 'fresh',
                'price_per_kg'=> 650.00,
                'stock_kg'    => 20.0,
                'is_available'=> true,
                'catch_date'  => Carbon::today(),
            ],
            [
                'user_id'     => $producer2->id,
                'name'        => 'King Prawns',
                'description' => 'Large tiger prawns caught from the Indian Ocean off the Mombasa coast.',
                'category'    => 'shellfish',
                'freshness'   => 'super_fresh',
                'price_per_kg'=> 1200.00,
                'stock_kg'    => 15.0,
                'is_available'=> true,
                'catch_date'  => Carbon::today(),
            ],
            [
                'user_id'     => $producer2->id,
                'name'        => 'Tuna Steaks',
                'description' => 'Fresh yellowfin tuna caught from the Indian Ocean. Perfect for sashimi or grilling.',
                'category'    => 'ocean',
                'freshness'   => 'fresh',
                'price_per_kg'=> 800.00,
                'stock_kg'    => 30.0,
                'is_available'=> true,
                'catch_date'  => Carbon::today()->subDay(),
            ],
            [
                'user_id'     => $producer2->id,
                'name'        => 'Smoked Tilapia',
                'description' => 'Traditionally smoked tilapia using mangrove wood. Rich smoky flavour, long shelf life.',
                'category'    => 'smoked',
                'freshness'   => 'smoked',
                'price_per_kg'=> 450.00,
                'stock_kg'    => 60.0,
                'is_available'=> true,
                'catch_date'  => Carbon::today()->subDays(5),
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }

        $tilapia    = Product::find(1);
        $omena      = Product::find(2);
        $perch      = Product::find(3);
        $prawns     = Product::find(4);
        $tuna       = Product::find(5);
        $smokedTila = Product::find(6);

        // ── 3. Questions ──────────────────────────────────────────────────────

        Question::create([
            'product_id'  => $tilapia->id,
            'consumer_id' => $consumer1->id,
            'producer_id' => $producer1->id,
            'question'    => 'Is the tilapia already gutted and scaled?',
            'answer'      => 'Yes! All our tilapia are fully cleaned — gutted, scaled, and washed before delivery.',
        ]);

        Question::create([
            'product_id'  => $tilapia->id,
            'consumer_id' => $consumer2->id,
            'producer_id' => $producer1->id,
            'question'    => 'Can you deliver to Nakuru?',
        ]);

        Question::create([
            'product_id'  => $prawns->id,
            'consumer_id' => $consumer1->id,
            'producer_id' => $producer2->id,
            'question'    => 'Are these deveined? And are they sold frozen or fresh?',
            'answer'      => 'They are sold fresh, not frozen. We can devein on request — just add a note when ordering.',
        ]);

        Question::create([
            'product_id'  => $tuna->id,
            'consumer_id' => $consumer2->id,
            'producer_id' => $producer2->id,
            'question'    => 'Is this sashimi-grade tuna?',
        ]);

        // ── 4. Orders ─────────────────────────────────────────────────────────

        // Order 1 — Brian, assigned to Peter, in transit
        $order1 = Order::create([
            'consumer_id'       => $consumer1->id,
            'delivery_agent_id' => $delivery1->id,
            'status'            => 'in_transit',
            'total_amount'      => 2150.00,
            'delivery_fee'      => 200.00,
            'delivery_address'  => 'Westlands, Nairobi',
            'delivery_notes'    => 'Call upon arrival, gate is blue',
        ]);

        OrderItem::create([
            'order_id'    => $order1->id,
            'product_id'  => $tilapia->id,
            'quantity_kg' => 3.0,
            'unit_price'  => 350.00,
            'subtotal'    => 1050.00,
        ]);

        OrderItem::create([
            'order_id'    => $order1->id,
            'product_id'  => $omena->id,
            'quantity_kg' => 5.0,
            'unit_price'  => 180.00,
            'subtotal'    => 900.00,
        ]);

        // Order 2 — Grace, no agent yet, confirmed
        $order2 = Order::create([
            'consumer_id'      => $consumer2->id,
            'status'           => 'confirmed',
            'total_amount'     => 2600.00,
            'delivery_fee'     => 200.00,
            'delivery_address' => 'Milimani, Nakuru',
        ]);

        OrderItem::create([
            'order_id'    => $order2->id,
            'product_id'  => $prawns->id,
            'quantity_kg' => 2.0,
            'unit_price'  => 1200.00,
            'subtotal'    => 2400.00,
        ]);

        // Order 3 — Brian, delivered
        $order3 = Order::create([
            'consumer_id'       => $consumer1->id,
            'delivery_agent_id' => $delivery1->id,
            'status'            => 'delivered',
            'total_amount'      => 1850.00,
            'delivery_fee'      => 200.00,
            'delivery_address'  => 'Kilimani, Nairobi',
            'created_at'        => Carbon::today()->subDays(3),
            'updated_at'        => Carbon::today()->subDays(2),
        ]);

        OrderItem::create([
            'order_id'    => $order3->id,
            'product_id'  => $tuna->id,
            'quantity_kg' => 2.0,
            'unit_price'  => 800.00,
            'subtotal'    => 1600.00,
        ]);

        // Add a GPS ping for order1
        \App\Models\DeliveryLocation::create([
            'order_id'          => $order1->id,
            'delivery_agent_id' => $delivery1->id,
            'latitude'          => -1.2676,
            'longitude'         => 36.8026,
            'status_update'     => 'On the way — Westlands roundabout',
        ]);
    }
}

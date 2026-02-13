<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LostItem;
use App\Models\FoundItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@lostandfound.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
        ]);

        // Create regular user
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'latitude' => 40.7580,
            'longitude' => -73.9855,
        ]);

        // Create some lost items
        LostItem::create([
            'user_id' => 2,
            'item_name' => 'iPhone 13 Pro',
            'description' => 'Black iPhone 13 Pro with leather case. Lost near Central Park.',
            'category' => 'Electronics',
            'date_lost' => now()->subDays(3),
            'latitude' => 40.7829,
            'longitude' => -73.9654,
            'status' => 'pending'
        ]);

        LostItem::create([
            'user_id' => 2,
            'item_name' => 'Wallet',
            'description' => 'Brown leather wallet with credit cards and ID inside.',
            'category' => 'Wallet',
            'date_lost' => now()->subDays(5),
            'latitude' => 40.7505,
            'longitude' => -73.9934,
            'status' => 'pending'
        ]);

        // Create some found items
        FoundItem::create([
            'user_id' => 1,
            'item_name' => 'Smartphone',
            'description' => 'Found a smartphone near subway station. Black color with case.',
            'category' => 'Electronics',
            'date_found' => now()->subDays(2),
            'latitude' => 40.7831,
            'longitude' => -73.9663,
            'status' => 'pending'
        ]);

        FoundItem::create([
            'user_id' => 1,
            'item_name' => 'Leather Wallet',
            'description' => 'Found brown leather wallet at coffee shop.',
            'category' => 'Wallet',
            'date_found' => now()->subDays(4),
            'latitude' => 40.7510,
            'longitude' => -73.9920,
            'status' => 'pending'
        ]);
    }
}
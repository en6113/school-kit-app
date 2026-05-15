<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminUserSeeder::class,
            VendorSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            SizeSeeder::class,
            ProductSizeSeeder::class,
            SchoolKitSeeder::class,
            SchoolKitItemSeeder::class,
            CartSeeder::class,
            OrderSeeder::class,
        ]);
    }
}

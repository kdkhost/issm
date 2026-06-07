<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CmsPermissionSeeder;
use Database\Seeders\CmsDefaultSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingsSeeder::class,
            OdsSeeder::class,
            CmsPermissionSeeder::class,
            CmsDefaultSeeder::class,
        ]);
    }
}

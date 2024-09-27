<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
                    
        $adminRole = Role::firstOrcreate(['name' => 'admin']);
        $freeRole = Role::firstOrcreate(['name' => 'free_user']);
        $premiumRole = Role::firstOrcreate(['name' => 'premium_user']);

        $manageSubscriptions = Permission::firstOrcreate(['name' => 'manage subscriptions']);
        $viewAllTransactions = Permission::firstOrcreate(['name' => 'view all transactions']);
        $revokePremiumAccess = Permission::firstOrcreate(['name' => 'revoke premium access']);
        $accessPremiumFeatures = Permission::firstOrcreate(['name' => 'access premium features']);

        $adminRole->givePermissionTo([
            $manageSubscriptions,
            $viewAllTransactions,
            $revokePremiumAccess,
            $accessPremiumFeatures,
        ]);

        $premiumRole->givePermissionTo($accessPremiumFeatures);


    }
}

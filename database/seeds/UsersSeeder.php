<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role Pemilik Toko
        $adminRole = Role::where('name', 'pemilik')->first();
        if (!$adminRole) {
            $adminRole = new Role();
            $adminRole->name = 'pemilik';
            $adminRole->display_name = 'Pemilik Toko';
            $adminRole->description = 'Akses penuh pemilik toko';
            $adminRole->save();
        }

        // Role Penjaga Toko (Kasir)
        $memberRole = Role::where('name', 'penjaga')->first();
        if (!$memberRole) {
            $memberRole = new Role();
            $memberRole->name = 'penjaga';
            $memberRole->display_name = 'Penjaga Toko';
            $memberRole->description = 'Akses operasional kasir dan stok';
            $memberRole->save();
        }

        // Sample Admin / Pemilik
        $admin = User::where('username', 'admin')->orWhere('email', 'admin@aisyah.com')->first();
        if (!$admin) {
            $admin = new User();
            $admin->name = 'Administrator';
            $admin->username = 'admin';
            $admin->email = 'admin@aisyah.com';
            $admin->password = bcrypt('AdminAi123');
            $admin->save();
            $admin->attachRole($adminRole);
        } else {
            $admin->username = 'admin';
            $admin->save();
            if (!$admin->hasRole('pemilik')) {
                $admin->attachRole($adminRole);
            }
        }

        // Sample Penjaga / Kasir
        $member = User::where('username', 'penjaga')->orWhere('email', 'member@aisyah.com')->first();
        if (!$member) {
            $member = new User();
            $member->name = 'Penjaga Toko';
            $member->username = 'penjaga';
            $member->email = 'member@aisyah.com';
            $member->password = bcrypt('member123');
            $member->save();
            $member->attachRole($memberRole);
        } else {
            $member->username = 'penjaga';
            $member->save();
            if (!$member->hasRole('penjaga')) {
                $member->attachRole($memberRole);
            }
        }
    }
}

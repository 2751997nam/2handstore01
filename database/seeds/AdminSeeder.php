<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mockery\CountValidator\Exception;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => 'Admin',
                'email' => 'iamadmin@test.com',
                'status' => 1,
                'password' => Hash::make('secret'),
                'role_id' => 1,
            ];

            User::create($data);

            $role = [
                'name' => 'admin',
            ];

            Role::create($role);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            
            return $e;
        }
    }
}

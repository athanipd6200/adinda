<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create('id_ID');
    	for($i = 1; $i <= 50; $i++){
    	      // insert data ke table pegawai menggunakan Faker
    		DB::table('users')->insert([
    			'name' => $faker->name,
    			'email' => $faker->email,
                'username' => $faker->username,
    			'password' => Hash::make('12345678'),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
    		]);
    	}
    }
}

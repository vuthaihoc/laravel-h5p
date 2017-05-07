<?php

use Illuminate\Database\Seeder;

class H5pUserSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('users')->insert([
            'name' => "User Creator",
            'email' => 'user@2by.kr',
            'password' => bcrypt('asd#123'),
        ]);
    }

}

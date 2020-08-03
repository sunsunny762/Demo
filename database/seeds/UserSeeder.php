<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_type_id' => 1,
            'password' => '$2y$10$jSAr/RwmjhwioDlJErOk9OQEO7huLz9O6Iuf/udyGbHPiTNuB3Iuy', //JohnDoe
            'first_name' => 'Sunny',
            'last_name' => 'Kalariya',
            'email' => 'sunsunny762@gmail.com',
            'status' => 1,
            'remember_token' => 'JNqJOeUGlpWdhUrRLgyU2Vvwkn6fkjNlKB0yPjbmSwTMjLAYIDP7QybkqnK0',
            'email_verified_at' => '2019-08-06 11:56:05',
            'created_at' => '2019-08-06 11:56:05',
            'updated_at' => '2019-08-06 11:56:05',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'              => 'DevSquad',
            'email'             => 'admin@devsquad.com',
            'password'          => bcrypt('password'),
            'remember_token'    => Str::random(10),
            'email_verified_at' => now(),
        ]);

        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
    }
}

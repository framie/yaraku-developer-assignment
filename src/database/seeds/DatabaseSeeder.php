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
        // Order is important as books rely on authors being created beforehand.
        $this->call([
            AuthorsTableSeeder::class,
            BooksTableSeeder::class
        ]);
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Author;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Seed the authors table in the database.
     *
     * @return void
     */
    public function run()
    {
        factory(Author::class, 10)->create();
    }
}

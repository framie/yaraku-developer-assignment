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
        Author::create([
            'name' => 'F. Scott Fitzgerald',
        ]);

        Author::create([
            'name' => 'George Orwell',
        ]);

        Author::create([
            'name' => 'J. R. R. Tolkien',
        ]);
    }
}

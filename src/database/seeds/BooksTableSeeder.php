<?php

use Illuminate\Database\Seeder;
use App\Author;
use App\Book;

class BooksTableSeeder extends Seeder
{
    /**
     * Seed the books table in the database.
     *
     * @return void
     */
    public function run()
    {
        // ensure that authors already exist in the database
        $fScottFitzgerald = Author::where('name', 'F. Scott Fitzgerald')->first();
        $georgeOrwell = Author::where('name', 'George Orwell')->first();
        $jrrTolkien = Author::where('name', 'J. R. R. Tolkien')->first();

        // add books if their respective authors exist
        if ($fScottFitzgerald) {
            Book::create([
                'title' => 'The Great Gatsby',
                'author_id' => $fScottFitzgerald->id,
                'published_at' => '1925-04-10'
            ]);
            Book::create([
                'title' => 'Tender Is the Night',
                'author_id' => $fScottFitzgerald->id,
                'published_at' => '1934-04-12'
            ]);
        }

        if ($georgeOrwell) {
            Book::create([
                'title' => 'Nineteen Eighty Four',
                'author_id' => $georgeOrwell->id,
                'published_at' => '1949-06-08'
            ]);
        }

        if ($jrrTolkien) {
            Book::create([
                'title' => 'The Lord of the Rings',
                'author_id' => $jrrTolkien->id,
                'published_at' => '1954-07-29'
            ]);
            Book::create([
                'title' => 'The Hobbit',
                'author_id' => $jrrTolkien->id,
                'published_at' => '1937-09-21'
            ]);
        }

    }
}

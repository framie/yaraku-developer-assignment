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
        $authors = Author::all();

        if (count($authors) > 0) {
            foreach ($authors as $author) {
                factory(Book::class, 2)->create(['author_id' => $author->id]);
            }
        }
    }
}

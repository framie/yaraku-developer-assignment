<?php

namespace Tests\Unit;

use App\Author;
use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authors seeder for successful data insertion.
     *
     * @return void
     */
    public function testAuthorsSeederInsertsData()
    {
        $this->seed(\AuthorsTableSeeder::class);

        $this->assertEquals(10, Author::count(), "Authors table should contain 10 authors");
    }

    /**
     * Test books seeder does not insert data if authors are not seeded first.
     *
     * @return void
     */
    public function testBooksSeederDoesntInsertWithoutAuthors()
    {
        $this->seed(\BooksTableSeeder::class);

        $this->assertEquals(0, Book::count(), "Books table should contain 0 books");
        $this->assertEquals(0, Author::count(), "Authors table should contain 0 authors");
    }

    /**
     * Test books seeder properly inserts data if authors are seeded first.
     *
     * @return void
     */
    public function testBooksSeederInsertsData()
    {
        $this->seed(\AuthorsTableSeeder::class);
        $this->seed(\BooksTableSeeder::class);

        $this->assertEquals(20, Book::count(), "Books table should contain 20 books");
        $this->assertEquals(10, Author::count(), "Authors table should contain 10 authors");
    }
}

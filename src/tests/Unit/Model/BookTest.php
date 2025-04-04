<?php

namespace Tests\Unit;

use App\Author;
use App\Book;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Book is created is related to a single author.
     *
     * @return void
     */
    public function testBookCreation()
    {
        $author = factory(Author::class)->create();
        factory(Book::class)->create([
            'title' => 'The Precision of the Agent of Death',
            'author_id' => $author->id,
            'published_at' => '2005-06-30'
        ]);

        $book = Book::first();
        $this->assertEquals('The Precision of the Agent of Death', $book->title);
        $this->assertEquals('2005-06-30', $book->published_at);
        $this->assertEquals($author->id, $book->author->id);
    }
    
    /**
     * Book title is required for creation.
     *
     * @return void
     */
    public function testBookTitleIsRequired()
    {
        $this->expectException(QueryException::class);

        factory(Book::class)->create(['title' => null]);
    }
    
    /**
     * Book author_id field is required for creation.
     *
     * @return void
     */
    public function testBookAuthorIdIsRequired()
    {
        $this->expectException(QueryException::class);
        
        factory(Book::class)->create(['author_id' => null]);
    }
    
    /**
     * Book author_id must map to an existing author.
     *
     * @return void
     */
    public function testBookAuthorIdMustBeValid()
    {
        $this->expectException(QueryException::class);
        
        // BigIncrements starts at 1.
        factory(Book::class)->create(['author_id' => 0]);
    }
    
    /**
     * Book published date is optional for creation.
     *
     * @return void
     */
    public function testBookPublishDateIsOptional()
    {
        factory(Book::class)->create([
            'published_at' => null
        ]);

        $book = Book::first();
        $this->assertEquals(null, $book->published_at);
    }
    
    /**
     * Duplicate book title cannot be used.
     *
     * @return void
     */
    public function testDuplicateBookTitleNotAllowed()
    {
        factory(Book::class)->create(['title' => 'The Odyssey']);

        $this->assertDatabaseHas('books', ['title' => 'The Odyssey']);

        $this->expectException(QueryException::class);
        factory(Book::class)->create(['title' => 'The Odyssey']);
    }
}

<?php

namespace Tests\Unit;

use App\Author;
use App\Book;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Author can be created.
     *
     * @return void
     */
    public function testAuthorCanBeCreated()
    {
        $author = factory(Author::class)->create(['name' => 'Isaka Kotaro']);

        $author = Author::first();
        $this->assertEquals('Isaka Kotaro', $author->name);
    }
    
    /**
     * Author name is required for creation.
     *
     * @return void
     */
    public function testAuthorNameRequiredForCreation()
    {
        $this->expectException(QueryException::class);

        factory(Author::class)->create(['name' => null]);
    }
    
    /**
     * Duplicate author name cannot be used.
     *
     * @return void
     */
    public function testDuplicateAuthorNameNotAllowed()
    {
        factory(Author::class)->create(['name' => 'Homer']);

        $this->assertDatabaseHas('authors', ['name' => 'Homer']);

        $this->expectException(QueryException::class);
        factory(Author::class)->create(['name' => 'Homer']);
    }
    
    /**
     * Author and book have a one-to-many relationship.
     *
     * @return void
     */
    public function testAuthorHasARelationshipWithBooks()
    {
        $author = factory(Author::class)->create();

        $author = Author::first();
        $book1 = factory(Book::class)->create(['author_id' => $author->id]);
        $book2 = factory(Book::class)->create(['author_id' => $author->id]);

        $books = $author->books;

        $this->assertCount(2, $books);
        $this->assertTrue($books->contains($book1));
        $this->assertTrue($books->contains($book2));
    }
    
    /**
     * Creates an author if not found when using findOrCreateByName().
     *
     * @return void
     */
    public function testAuthorIsCreatedIfNotFound()
    {
        $name = 'Test Name';
        $author = Author::findOrCreateByName($name);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals($name, $author->name);
        $this->assertDatabaseHas('authors', ['name' => $name]);
    }
    
    /**
     * Does not create author if already existing when using findOrCreateByName().
     *
     * @return void
     */
    public function testAuthorIsNotCreatedIfFound()
    {
        $author = factory(Author::class)->create();
        $found = Author::findOrCreateByName($author->name);

        $this->assertSame($author->id, $found->id);
    }
}

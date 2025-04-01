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
     * Test author creation.
     *
     * @return void
     */
    public function testAuthorCreation()
    {
        $author = factory(Author::class)->create(['name' => 'Isaka Kotaro']);

        $author = Author::first();
        $this->assertEquals('Isaka Kotaro', $author->name);
    }
    
    /**
     * Test author name is required.
     *
     * @return void
     */
    public function testAuthorNameIsRequired()
    {
        $this->expectException(QueryException::class);

        factory(Author::class)->create(['name' => null]);
    }
    
    /**
     * Test duplicate author name cannot be used.
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
     * Test author and book relationship.
     *
     * @return void
     */
    public function testAuthorAndBookRelationship()
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
     * Test creates an author if not found.
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
     * Test does not create author if already existing.
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

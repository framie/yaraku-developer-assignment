<?php

namespace Tests\Unit;

use App\Author;
use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test author creation
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
     * Test author name is required
     *
     * @return void
     */
    public function testAuthorNameIsRequired()
    {
        $this->expectException(QueryException::class);

        factory(Author::class)->create(['name' => null]);
    }
    
    /**
     * Test duplicate author name cannot be used
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
}

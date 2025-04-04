<?php

namespace Tests\Unit;

use App\Author;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Books are displayed properly.
     *
     * @return void
     */
    public function testBooksAreDisplayedProperly()
    {
        $book = factory(Book::class)->create();
        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    /**
     * Books can be stored.
     *
     * @return void
     */
    public function testBooksCanBeStored()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $author = factory(Author::class)->create();
        $data = [
            'title' => 'One Piece',
            'author_name' => $author->name,
            'publish_date' => '1997-07-22'
        ];
        $response = $this->post(route('books.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Book added successfully.');
        $this->assertDatabaseHas('books', [
            'title' => 'One Piece',
            'author_id' => $author->id
        ]);
    }

    /**
     * Book data is properly validated before attempting to store.
     *
     * @return void
     */
    public function testBookDataIsValidatedWhenStoring()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $data = [
            'title' => '',
            'author_name' => ''
        ];
        $response = $this->post(route('books.store'), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'author_name']);
    }

    /**
     * Publish date is not stored when invalid.
     *
     * @return void
     */
    public function testPublishDateIsNotStoredIfInvalid()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $data = [
            'title' => 'House of Leaves',
            'author_name' => 'Mark Z. Danielewski',
            'publish_date' => 'Invalid Date'
        ];
        $response = $this->post(route('books.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('books', [
            'title' => 'House of Leaves',
            'published_at' => null
        ]);
    }

    /**
     * An existing book can be updated.
     *
     * @return void
     */
    public function testExistingBookCanBeUpdated()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $book = factory(Book::class)->create();
        $newTitle = 'Fullmetal Alchemist';
        $response = $this->put(route('books.update', $book->id), [
            'title' => $newTitle,
            'author_name' => $book->author->name,
            'publish_date' => '2001-07-12'
        ]);

        $response->assertStatus(302);
        $book->refresh();
        $this->assertEquals($newTitle, $book->title);
        $this->assertEquals('2001-07-12', $book->published_at);
    }

    /**
     * Not found response when updating non existent book.
     *
     * @return void
     */
    public function testNotFoundResponseForUpdatingNonExistentBook()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->put(route('books.update', 0));

        $response->assertStatus(404);
    }

    /**
     * An existing book can be deleted.
     *
     * @return void
     */
    public function testAnExistingBookCanBeDeleted()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $book = factory(Book::class)->create();
        $response = $this->delete(route('books.destroy', $book->id));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /**
     * Not found response when deleting non existent book.
     *
     * @return void
     */
    public function testNotFoundResponseForDeletingNonExistentBook()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->delete(route('books.destroy', 0));

        $response->assertStatus(404);
    }

    /**
     * Exports books data as CSV.
     *
     * @return void
     */
    public function testExportsBookDataAsCsv()
    {
        $book = factory(Book::class)->create();
        $response = $this->get(route('books.export', [
            'format' => 'csv', 'type' => 'titles'
        ]));
        $contentType = $response->headers->get('Content-Type');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $contentType);
    }

    /**
     * Exports books data as XML.
     *
     * @return void
     */
    public function testExportsBookDataAsXml()
    {
        $book = factory(Book::class)->create();
        $response = $this->get(route('books.export', [
            'format' => 'xml', 'type' => 'titles'
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }

    /**
     * Bad request response when attempting to export using invalid format.
     *
     * @return void
     */
    public function testBadRequestResponseForInvalidExportFormat()
    {
        $response = $this->get(route('books.export', [
            'format' => 'invalid', 'type' => 'titles'
        ]));

        $response->assertStatus(400);
    }

    /**
     * Bad request response when attempting to export using invalid type.
     *
     * @return void
     */
    public function testBadRequestResponseForInvalidExportType()
    {
        $response = $this->get(route('books.export', [
            'format' => 'csv', 'type' => 'invalid'
        ]));

        $response->assertStatus(400);
    }
}

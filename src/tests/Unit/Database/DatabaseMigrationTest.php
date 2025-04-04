<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class DatabaseMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authors table has the expected schema.
     *
     * @return void
     */
    public function testAuthorsTableHasCorrectSchema()
    {
        $columns = ['id', 'name', 'created_at', 'updated_at'];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('authors', $column),
                "Column '$column' does not exist in the authors table."
            );
        }
    }

    /**
     * Books table has the expected schema.
     *
     * @return void
     */
    public function testBooksTableHasCorrectSchema()
    {
        $columns = ['id', 'title', 'author_id', 'published_at', 'created_at', 'updated_at'];

        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('books', $column),
                "Column '$column' does not exist in the books table."
            );
        }
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key in Laravel 6.
            $table->string('title')->unique(); // Assume each book title is unique.
            $table->unsignedBigInteger('author_id'); // Laravel 7+ would use foreignId().
            $table->date('published_at')->nullable(); // Nullable makes this field optional.
            $table->timestamps(); // Adds created_at and updated_at columns.

            // Define author_id as foreign key.
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');

            // Add index to the 'title' column as it will be used for filtering.
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}

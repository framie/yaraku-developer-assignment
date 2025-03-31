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
            $table->bigIncrements('id'); // primary key in Laravel 6
            $table->string('title')->unique(); // assume each book title is unique
            $table->unsignedBigInteger('author_id'); // Laravel 7+ would use foreignId()
            $table->date('published_at')->nullable(); // nullable makes this field optional
            $table->timestamps(); // adds created_at and updated_at columns

            // define author_id as foreign key
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');

            // add index to the 'title' column as it will be used for filtering
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

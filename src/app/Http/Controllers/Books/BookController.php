<?php

namespace App\Http\Controllers\Books;

use App\Author;
use App\Book;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Book Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling the logic to view or make
    | changes to books within the database. Includes functionality to sort by
    | Book Title, Book Publish Date or Author Name.
    |
    */

    /**
     * Display all books
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // get the sort parameters
        $sortBy = $request->input('sort_by');
        $order = $request->input('order');
        $pageSize = 10;

        // mapping of sortBy values to queries
        $validSortByValues = ['title', 'author_name', 'publish_date'];
        
        // validate request input values
        if (!in_array($sortBy, $validSortByValues)) {
            $sortBy = 'title';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        // build SQL query based on request params
        $query = Book::query();
        if ($sortBy == 'author_name') {
            $query->leftJoin('authors', 'books.author_id', '=', 'authors.id');
            $query->orderBy('authors.name', $order);
        } else {
            $query->with('author');
            $query->orderBy($sortBy == 'title' ? 'books.title' : 'books.published_at', $order);
        }

        // retrieve current page of results
        $books = $query->paginate($pageSize);
        $from = ($books->currentPage() - 1) * $pageSize + 1;
        $to = min($from + $pageSize - 1, $books->total());

        // if request is ajax, return JSON response
        if ($request->ajax()) {
            return response()->json([
                'books' => $books,
                'sortBy' => $sortBy,
                'order' => $order
            ]);
        }

        return view('books.index', compact('books', 'sortBy', 'order', 'from', 'to'));
    }
}

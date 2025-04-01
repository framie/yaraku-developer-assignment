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
    | Book Title, Book Publish Date or Author Name, as well as add new or
    | delete existing books.
    |
    */

    /**
     * Display all books.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get the sort parameters.
        $sort = $request->input('sort');
        $order = $request->input('order');
        $pageSize = 10;

        // Mapping of sort values to queries.
        $validSortValues = ['title', 'author_name', 'publish_date'];
        
        // Validate request input values
        if (!in_array($sort, $validSortValues)) {
            $sort = 'title';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        // Build SQL query based on request params.
        $query = Book::query();
        if ($sort == 'author_name') {
            $query->leftJoin('authors', 'books.author_id', '=', 'authors.id');
            $query->orderBy('authors.name', $order);
        } else {
            $query->with('author');
            $query->orderBy($sort == 'title' ? 'books.title' : 'books.published_at', $order);
            if ($sort == 'publish_date') {
                $query->whereNotNull('books.published_at');
            }
        }

        // Retrieve current page of results.
        $books = $query->paginate($pageSize);
        $from = ($books->currentPage() - 1) * $pageSize + 1;
        $to = min($from + $pageSize - 1, $books->total());

        // If request is ajax, return JSON response.
        if ($request->ajax()) {
            return response()->json([
                'books' => $books,
                'sort' => $sort,
                'order' => $order
            ]);
        }

        return view('books.index', compact('books', 'sort', 'order', 'from', 'to'));
    }

    /**
     * Create and store a new book in the database.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validates the data and will automatically send 422 error response on failure.
        $validatedData = $request->validate([
            'title' => 'required|unique:books,title|max:255',
            'author_name' => 'required|string|max:255',
        ], [
            'title.unique' => 'A book with this title already exists.',
            'title.required' => 'The book title is required.',
            'author_name.required' => 'The book author is required.',
        ]);

        // Create author if not already existing.
        $author = Author::findOrCreateByName($request->input('author_name'));
        $publishDate = $request->input('publish_date');

        $book = Book::create([
            'title' => $request->input('title'),
            'author_id' => $author->id,
            'published_at' => strtotime($publishDate) ? $publishDate : null
        ]);

        $message = 'Book added successfully.';

        // If request is ajax, return JSON response.
        if ($request->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $message,
                'data' => $book
            ]);
        }

        return redirect()->route('books.index')->with('message', $message);
    }

    /**
     * Removes a book from the database.
     *
     * @param  \Illuminate\Http\Request
     * @param  \App\Book
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Book $book)
    {
        // No need to validate as it is done via the route.
        $book->delete();

        $message = 'Book deleted successfully.';

        // If request is ajax, return JSON response.
        if ($request->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $message
            ]);
        }

        return redirect()->route('books.index')->with('message', $message);
    }
}

<?php

namespace App\Http\Controllers\Books;

use SimpleXMLElement;
use App\Author;
use App\Book;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

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
        $search = $request->input('search');
        $pageSize = 10;

        // Mapping of sort values to queries.
        $sortMapping = [
            'title' => 'books.title',
            'author_name' => 'authors.name',
            'publish_date' => 'books.published_at'
        ];
        
        // Validate request input values.
        if (!in_array($sort, array_keys($sortMapping))) {
            $sort = 'title';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        // Build SQL query based on request params.
        $query = Book::query();

        // If sorting or filtering via Author column then must perform a join.
        if ($sort === 'author_name' || $search) {
            $query->select('books.id as book_id', 'books.*', 'authors.name as author_name');
            $query->leftJoin('authors', 'books.author_id', '=', 'authors.id');
        } else {
            $query->with('author');
        }

        // If search term is included do partial search on Book Title and Author Name.
        // Ensure that input is sanitized to prevent SQL injection.
        if ($search) {
            $query->where($sortMapping['title'], 'LIKE', "%{$search}%")
                  ->orWhere($sortMapping['author_name'], 'LIKE', "%{$search}%");
        }

        // Ensure that results are ordered by specified sort column and order.
        $query->orderBy($sortMapping[$sort], $order);
        if ($sort === 'publish_date') {
            $query->whereNotNull('books.published_at');
        }

        // Retrieve current page of results.
        $books = $query->paginate($pageSize);
        $currentPage = $books->currentPage();
        $lastPage = $books->lastPage();

        // If request is ajax, return JSON response.
        if ($request->ajax()) {
            return response()->json([
                'books' => $books,
                'sort' => $sort,
                'order' => $order,
                'search' => $search
            ]);
        }

        return view('books.index', compact(
            'books', 'sort', 'order', 'search', 'currentPage', 'lastPage'
        ));
    }

    /**
     * Create and store a new book in the database.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validates the data.
        $data = $request->only('title', 'author_name');
        $validator = Validator::make($data, [
            'title' => 'required|unique:books,title|max:255',
            'author_name' => 'required|string|max:255',
            'publish_date' => 'nullable|date_format:Y-m-d'
        ]);

        // Return a 422 response if validation fails.
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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

        return redirect()->route('books.index')->with(['message' => $message]);
    }

    /**
     * Removes a book from the database.
     *
     * @param  \Illuminate\Http\Request
     * @param  \App\Book  $book - The book to be removed.
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

    /**
     * Updates an existing book in the database.
     *
     * @param  \Illuminate\Http\Request
     * @param  \App\Book  $book - The book to be updated.
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Book $book)
    {
        // Validates the data.
        $data = $request->only('title', 'author_name', 'publish_date');

        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'author_name' => 'required|string|max:255',
            'publish_date' => 'nullable|date_format:Y-m-d'
        ]);

        // Return a 422 response if validation fails.
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create author if not already existing.
        $author = Author::findOrCreateByName($request->input('author_name'));
        $publishDate = $request->input('publish_date');

        $book->update([
            'title' => $data['title'],
            'author_id' => $author->id,
            'published_at' => strtotime($publishDate) ? $publishDate : null
        ]);

        $message = 'Book updated successfully.';

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
     * Exports book or author data in specified format.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $format - The export format ('csv' or 'xml').
     * @param  string  $type - The type of data to export ('titles', 'authors', 'all').
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request, $format, $type)
    {
        // Generate data based on $type.
        if ($type === 'titles') {
            $books = Book::with('author')->get();
            $data = $books->pluck('title')
                ->map(fn($title) => ['Book Title' => $title])
                ->toArray();
        } elseif ($type === 'authors') {
            $authors = Author::all();
            $data = $authors->map(fn($author) => [
                'Name' => $author->name
            ])->toArray();
        } elseif ($type === 'all') {
            $books = Book::with('author')->get();
            $data = $books->map(fn($book) => [
                'Title'        => $book->title,
                'Author Name'  => $book->author->name
            ])->toArray();
        } else {
            return abort(400, 'Invalid export type.');
        }

        // Return output based on $format.
        if ($format === 'csv') {
            return $this->exportCsv($data, $type);
        } elseif ($format === 'xml') {
            return $this->exportXml($data, $type);
        }
        return abort(400, 'Invalid export format.');
    }

    /**
     * Generates and streams a CSV file containing the exported data.
     *
     * @param  array  $data - The data to export.
     * @param  string  $type - The type of data ('titles', 'authors', 'all').
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function exportCsv($data, $type)
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$type}.csv",
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];

        // Add headers.
        array_unshift($data, array_keys($data[0]));

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        // Will not save the file on the server but instead stream directly to user.
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Generates and returns an XML file containing the exported data.
     *
     * @param  array  $data - The data to export.
     * @param  string  $type - The type of data ('titles', 'authors', 'all').
     * @return \Illuminate\Http\Response
     */
    private function exportXml($data, $type)
    {
        $nameMap = [
            'titles' => 'Title',
            'authors' => 'Author',
            'all' => 'Book'
        ];
        $name = $nameMap[$type] ?? 'Item';
        $xml = new SimpleXMLElement("<{$name}s/>");

        foreach ($data as $row) {
            $item = $xml->addChild($name);
            foreach ($row as $key => $value) {
                $item->addChild(str_replace(' ', '', $key), htmlspecialchars($value));
            }
        }

        return response($xml->asXML(), 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => "attachment; filename={$type}.xml"
        ]);
    }
}

<div id="book-table" data-url="{{ route('books.index') }}">
    @csrf
    <div class="search-container">
        <span>Search
        <div class="search-group">
            <label for="author">Book Title:</label>
            <input type="text" name="book_title" class="input-text" required>
        </div>
        <div class="search-group">
            <label for="author">Author Name:</label>
            <input type="text" name="author_name" class="input-text" required>
        </div>
        <button
            onclick="buttonHandler(this)"
            data-key="search"
            data-value="submit"
        >
            Submit
        </button>
        <button
            onclick="buttonHandler(this)"
            data-key="search"
            data-value="reset"
        >
            Reset
        </button>

    </div>
    <table>
        <thead>
            <tr>
                <th>
                    <button
                        onclick="buttonHandler(this)"
                        class="button-sort{{ $sort == 'title' ? ' button-sort--active' . ($order == 'desc' ? ' button-sort--desc' : ' button-sort--asc') : '' }}"
                        data-key="sort"
                        data-value="title"
                    >
                        Title
                    </button>
                </th>
                <th>
                    <button
                        onclick="buttonHandler(this)"
                        class="button-sort{{ $sort == 'author_name' ? ' button-sort--active' . ($order == 'desc' ? ' button-sort--desc' : ' button-sort--asc') : '' }}"
                        data-key="sort"
                        data-value="author_name"
                    >
                        Author Name
                    </button>
                </th>
                <th>
                    <button
                        onclick="buttonHandler(this)"
                        class="button-sort{{ $sort == 'publish_date' ? ' button-sort--active' . ($order == 'desc' ? ' button-sort--desc' : ' button-sort--asc') : '' }}"
                        data-key="sort"
                        data-value="publish_date"
                    >
                        Publish Date
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="book-list">
            @foreach ($books as $book)
                <tr class="book-row" data-book-id="{{ $book->id }}">
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author->name }}</td>
                    <td>{{ $book->published_at }}</td>
                    <td>
                        <button
                            onclick="modifyHandler(this)"
                            class="button-book--modify"
                        >
                            Modify
                        </button>
                    </td>
                    <td>
                        <button
                            onclick="deleteHandler(this)"
                            class="button-book--delete"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        <button
            onclick="buttonHandler(this)"
            class="pagination-prev{{ $books->onFirstPage() ? ' disabled' : '' }}"
            data-key="page"
            data-value="prev"
        >
            Previous
        </button>

        <span class="pagination-from">{{$from}}</span>
        -
        <span class="pagination-to">{{$to}}</span>
        /
        <span class="pagination-total">{{$books->total()}}</span>

        <button
            onclick="buttonHandler(this)"
            class="pagination-next{{ $books->hasMorePages() ? '' : ' disabled' }}"
            data-key="page"
            data-value="next"
        >
            Next
        </button>
    </div>
</div>

<script src="{{ asset('js/book-table-script.js') }}"></script>

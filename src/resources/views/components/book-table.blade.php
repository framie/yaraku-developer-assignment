<div id="book-table" data-url="{{ route('books.index') }}">
    <table>
        <thead>
            <tr>
                <th>
                    <button
                        onclick="buttonHandler('sortBy', 'title')"
                        class="button-sort{{ $sortBy == 'title' ? ' button-sort--active' : '' }}{{ $order == 'desc' ? ' button-sort--desc' : ' button-sort--asc' }}"
                        data-sort="title"
                    >
                        Title
                    </button>
                </th>
                <th>
                    <button
                        onclick="buttonHandler('sortBy', 'author_name')"
                        class="button-sort{{ $sortBy == 'author_name' ? ' button-sort--active' : '' }}{{ $order == 'desc' ? ' button-sort--desc' : ' button-sort--asc' }}"
                        data-sort="author_name"
                    >
                        Author Name
                    </button>
                </th>
                <th>
                    <button
                        onclick="buttonHandler('sortBy', 'publish_date')"
                        class="button-sort{{ $sortBy == 'publish_date' ? ' button-sort--active' : '' }}{{ $order == 'desc' ? ' button-sort--desc' : ' button-sort--asc' }}"
                        data-sort="publish_date"
                    >
                        Publish Date
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="book-list">
        @foreach ($books as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author->name }}</td>
                    <td>{{ $book->published_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        <button onclick="buttonHandler('page', 'prev')"
            class="pagination-prev {{ $books->onFirstPage() ? 'disabled' : '' }}">
            Previous
        </button>

        <span class="pagination-from">{{$from}}</span>
        -
        <span class="pagination-to">{{$to}}</span>
        /
        <span class="pagination-total">{{$books->total()}}</span>

        <button onclick="buttonHandler('page', 'next')"
            class="pagination-next {{ $books->hasMorePages() ? '' : 'disabled' }}">
            Next
        </button>
    </div>
</div>

<script src="{{ asset('js/book-table-script.js') }}"></script>

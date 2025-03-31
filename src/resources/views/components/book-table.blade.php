<div id="book-table">
    <table>
        <thead>
            <tr>
                <th>
                    <a href="{{ route('books.index', [
                        'order' => $order == 'desc' || $sortBy != 'title' ? 'asc' : 'desc',
                        'sort_by' => 'title'
                    ]) }}">
                        Title
                    </a>
                </th>
                <th>
                    <a href="{{ route('books.index', [
                        'order' => $order == 'desc' || $sortBy != 'author_name' ? 'asc' : 'desc',
                        'sort_by' => 'author_name'
                    ]) }}">
                        Author Name
                    </a>
                </th>
                <th>
                    <a href="{{ route('books.index', [
                        'order' => $order == 'desc' || $sortBy != 'publish_date' ? 'asc' : 'desc',
                        'sort_by' => 'publish_date'
                    ]) }}">
                        Publish Date
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
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
        <a href="{{ $books->previousPageUrl() ?? '#' }}" 
            class="{{ $books->onFirstPage() ? 'disabled' : '' }}">
            Previous
        </a>

        {{$from}}-{{$to}} / {{$books->total()}}

        <a href="{{ $books->nextPageUrl() ?? '#' }}" 
            class="{{ $books->hasMorePages() ? '' : 'disabled' }}">
            Next
        </a>
    </div>

</div>
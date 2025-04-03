<div id="book-table" data-url="{{ route('books.index') }}">
    @csrf

    @include('components.search', ['items' => $books])

    <table>
        <thead>
            <tr>
                <th>
                    <button
                        type="button"
                        class="button-sort{{ $sort == 'title' ? ' button-sort--active' . ($order == 'desc' ? ' button-sort--desc' : ' button-sort--asc') : '' }}"
                        onclick="buttonHandler(this)"
                        data-key="sort"
                        data-value="title"
                    >
                        Title
                    </button>
                </th>
                <th>
                    <button
                        type="button"
                        class="button-sort{{ $sort == 'author_name' ? ' button-sort--active' . ($order == 'desc' ? ' button-sort--desc' : ' button-sort--asc') : '' }}"
                        onclick="buttonHandler(this)"
                        data-key="sort"
                        data-value="author_name"
                    >
                        Author Name
                    </button>
                </th>
                <th>
                    <button
                        type="button"
                        class="button-sort{{ $sort == 'publish_date' ? ' button-sort--active' . ($order == 'desc' ? ' button-sort--desc' : ' button-sort--asc') : '' }}"
                        onclick="buttonHandler(this)"
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
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author->name }}</td>
                    <td>{{ $book->published_at }}</td>
                    <td>
                        <button
                            type="button"
                            class="button-book--modify"
                            onclick="modifyHandler(this)"
                            data-book-id="{{ $book->id }}"
                            data-title="{{ $book->title }}"
                            data-author-name="{{ $book->author->name }}"
                            data-publish-date="{{ $book->published_at }}"
                        >
                            Modify
                        </button>
                    </td>
                    <td>
                        <button
                            type="button"
                            class="button-book--delete"
                            onclick="deleteHandler(this)"
                            data-book-id="{{ $book->id }}"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('components.pagination', ['items' => $books])

</div>

@include('components.book-modal', ['type' => 'modify'])

<script src="{{ asset('js/book-table-script.js') }}"></script>

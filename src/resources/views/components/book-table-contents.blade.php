<table class="book-table__table">
    <thead>
        <tr class="book-table__row">
            <th class="book-table__header">
                <button
                    type="button"
                    class="button--sort{{ $sort === 'title' ? ' button--sort--active' . ($order === 'desc' ? ' button--sort--desc' : ' button--sort--asc') : '' }}"
                    onclick="buttonHandler(this)"
                    data-key="sort"
                    data-value="title"
                >
                    Book Title
                </button>
            </th>
            <th class="book-table__header">
                <button
                    type="button"
                    class="button--sort{{ $sort === 'author_name' ? ' button--sort--active' . ($order === 'desc' ? ' button--sort--desc' : ' button--sort--asc') : '' }}"
                    onclick="buttonHandler(this)"
                    data-key="sort"
                    data-value="author_name"
                >
                    Author Name
                </button>
            </th>
            <th class="book-table__header">
                <button
                    type="button"
                    class="button--sort{{ $sort === 'publish_date' ? ' button--sort--active' . ($order === 'desc' ? ' button--sort--desc' : ' button--sort--asc') : '' }}"
                    onclick="buttonHandler(this)"
                    data-key="sort"
                    data-value="publish_date"
                >
                    Publish Date
                </button>
            </th>
            <th class="book-table__header"/>
            <th class="book-table__header"/>
        </tr class="book-table__row">
    </thead>
    <tbody id="book-list">
        @foreach ($books as $book)
            <tr class="book-table__row">
                <td class="book-table__cell">{{ $book->title }}</td>
                <td class="book-table__cell">{{ $book->author->name }}</td>
                <td class="book-table__cell">{{ $book->published_at }}</td>
                <td class="book-table__cell">
                    <button
                        type="button"
                        class="button button--modify"
                        onclick="modifyHandler(this)"
                        data-book-id="{{ $book->id }}"
                        data-title="{{ $book->title }}"
                        data-author-name="{{ $book->author->name }}"
                        data-publish-date="{{ $book->published_at }}"
                    >
                        Modify
                    </button>
                </td>
                <td class="book-table__cell">
                    <button
                        type="button"
                        class="button button--delete"
                        onclick="deleteHandler(this)"
                        data-book-id="{{ $book->id }}"
                    >
                        Delete
                    </button>
                </td>
            </tr class="book-table__row">
        @endforeach
    </tbody>
</table>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/book-table.css') }}">
@endpush

<div id="book-table" class="book-table" data-url="{{ route('books.index') }}">
    @csrf

    <div class="book-table__container">

        <div class="menu-container">
            @include('components.book-menu')
        </div>

        @include('components.book-table-contents')

        <div class="panel panel__pagination">
            @include('components.pagination', ['items' => $books])
        </div>

    </div>

</div>

@include('components.book-modal', ['type' => 'modify'])

<script src="{{ asset('js/book-table-script.js') }}"></script>

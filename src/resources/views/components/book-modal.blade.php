<div id="book-{{ $type }}-modal" class="modal fade">
    <form
        id="book-{{ $type }}-form"
        method="POST"
        action="{{ route('books.store') }}"
        onsubmit="return false;"
    >
        @csrf
        @if ($type == 'modify')
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="book-{{ $type }}-title">Book Title:</label>
            <input
                id="book-{{ $type }}-title"
                name="title"
                type="text"
                class="input-text"
            >
        </div>
        <div class="form-group">
            <label for="book-{{ $type }}-author-name">Author Name:</label>
            <input
                id="book-{{ $type }}-author-name"
                name="author_name"
                type="text"
                class="input-text"
            >
        </div>
        <div class="form-group">
            <label for="book-{{ $type }}-publish-date">Publish Date (optional):</label>
            <input
                id="book-{{ $type }}-publish-date"
                name="publish_date"
                type="date"
                class="input-date"
            >
        </div>
        <div class="form-group">
            @if ($type == 'create')
                <button
                    type="submit"
                    class="input-button"
                    onclick="return submitBookCreateForm()"
                >
                    Add Book
                </button>
            @elseif ($type == 'modify')
                <input type="hidden" id="book-modify-id">
                <button
                    type="submit"
                    class="input-button"
                    onclick="return submitBookModifyForm()"
                >
                    Confirm
                </button>
            @endif
        </div>
        <div class="form-group">
            <span class="message"></span>
        </div>
    </form>
</div>

@if ($type == 'create')
    <script src="{{ asset('js/book-create-script.js') }}"></script>
@elseif ($type == 'modify')
    <script src="{{ asset('js/book-modify-script.js') }}"></script>
@endif

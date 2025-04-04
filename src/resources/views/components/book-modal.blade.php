@if ($type === 'create')
    <button type="button" class="button" onclick="openModal('book-create-modal')">
        Add a book
    </button>
@endif

<div
    id="book-{{ $type }}-modal"
    class="modal--container fade"
    onclick="closeModal('book-{{ $type }}-modal', event)"
>
    <div class="modal">
        <button
            class="modal__close"
            onclick="closeModal('book-{{ $type }}-modal')"
            type="button"
        >
            X
        </button> 
        <form
            id="book-{{ $type }}-form"
            class="modal__form"
            method="POST"
            action="{{ route('books.store') }}"
            onsubmit="return false;"
        >
            @csrf
            @if ($type === 'modify')
                @method('PUT')
            @endif
            <span class="modal__title">{{ ucfirst($type) }} a Book</span>
            <div class="modal__form-group">
                <label
                    class="modal__form-label"
                    for="book-{{ $type }}-title"
                >
                    Book Title:
                </label>
                <input
                    id="book-{{ $type }}-title"
                    name="title"
                    type="text"
                    class="input-text"
                >
            </div>
            <div class="modal__form-group">
                <label
                    class="modal__form-label"
                    for="book-{{ $type }}-author-name"
                >
                    Author Name:
                </label>
                <input
                    id="book-{{ $type }}-author-name"
                    name="author_name"
                    type="text"
                    class="input-text"
                >
            </div>
            <div class="modal__form-group">
                <label
                    class="modal__form-label"
                    for="book-{{ $type }}-publish-date"
                >
                    Publish Date (optional):
                </label>
                <input
                    id="book-{{ $type }}-publish-date"
                    name="publish_date"
                    type="date"
                    class="input-date"
                >
            </div>
            <div class="modal__form-group">
                @if ($type === 'create')
                    <button
                        type="submit"
                        class="button button--confirm"
                        onclick="return submitBookCreateForm()"
                    >
                        Add Book
                    </button>
                @elseif ($type === 'modify')
                    <input type="hidden" id="book-modify-id">
                    <button
                        type="submit"
                        class="button button--confirm"
                        onclick="return submitBookModifyForm()"
                    >
                        Confirm
                    </button>
                @endif
            </div>
            <div class="modal__form-group">
                <span class="message"></span>
            </div>
        </form>
    </div>
</div>

@if ($type === 'create')
    <script src="{{ asset('js/book-create-script.js') }}"></script>
@elseif ($type === 'modify')
    <script src="{{ asset('js/book-modify-script.js') }}"></script>
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush

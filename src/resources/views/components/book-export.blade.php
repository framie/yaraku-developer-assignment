<button type="button" class="button" onclick="openModal('book-export-modal')">
    Export data
</button>

<div
    id="book-export-modal"
    class="book-export-modal modal--container fade"
    onclick="closeModal('book-export-modal', event)"
>
    <div class="modal">
        <button
            class="modal__close"
            onclick="closeModal('book-export-modal')"
            type="button"
        >
            X
        </button> 
        <form id="book-export-form" class="modal__form" action="{{ route('books.index') }}">
            <span class="modal__title">Export Data</span>
            <div class="modal__form-group">
                <label class="modal__form-label" for="type">Data:</label>
                <select class="select" title="type" name="type">
                    <option value="all">Books and Authors</option>
                    <option value="titles">Book Titles only</option>
                    <option value="authors">Author Names only</option>
                </select>
            </div>
                
            <div class="modal__form-group">
                <label class="modal__form-label" for="format">Format:</label>
                <select class="select" title="format" name="format">
                    <option value="csv">CSV</option>
                    <option value="xml">XML</option>
                </select>
            </div>

            <div class="modal__form-group">
                <button
                    class="button button--confirm"
                    type="button"
                    onclick="exportHandler();"
                >
                    Export Data
                </button>
            </div>
            <div class="modal__form-group">
                <span class="message"></span>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/book-export-script.js') }}"></script>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush

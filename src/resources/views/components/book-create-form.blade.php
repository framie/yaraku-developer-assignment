<div>
    <form id="book-create-form" method="POST" action="/books">
        @csrf
        <div class="form-group">
            <label for="title">Book Title:</label>
            <input type="text" name="title" class="input-text" required>
        </div>
        <div class="form-group">
            <label for="author">Author Name:</label>
            <input type="text" name="author_name" class="input-text" required>
        </div>
        <div class="form-group">
            <label for="publish-date">Publish Date (optional):</label>
            <input type="date" name="publish_date" class="input-date">
        </div>
        <div class="form-group">
            <button class="input-button" onclick="return submitBookCreateForm()">
                Add Book
            </button>
        </div>
        <div class="form-group">
            <span id="book-create-message"></span>
        </div>
    </form>
</div>

<script src="{{ asset('js/book-create-form-script.js') }}"></script>

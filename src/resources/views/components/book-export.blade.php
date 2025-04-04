<form id="book-export-form" action="{{ route('books.index') }}">
    <label for="type">Data:</label>
    <select name="type">
        <option value="all">Book Titles and Author Names</option>
        <option value="titles">Book Titles only</option>
        <option value="authors">Author Names only</option>
    </select>
    
    <label for="format">Format:</label>
    <select name="format">
        <option value="csv">CSV</option>
        <option value="xml">XML</option>
    </select>

    <button type="button" onclick="exportHandler();">Export Data</button>
</form>

<script src="{{ asset('js/book-export-script.js') }}"></script>

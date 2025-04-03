<div class="search-container">
    <label for="search">Search:</label>
    <input
        id="search-input"
        type="text"
        name="search"
        class="input-text"
        value="{{ $search }}"
    >
    <button
        type="button"
        onclick="buttonHandler(this)"
        data-key="search"
        data-value="submit"
    >
        Submit
    </button>
    <button
        type="button"
        onclick="buttonHandler(this)"
        data-key="search"
        data-value="reset"
    >
        Reset
    </button>
</div>
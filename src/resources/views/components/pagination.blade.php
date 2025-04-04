<div class="pagination-container">
    <div class="pagination panel">
        <button
            type="button"
            class="pagination-prev{{ $items->onFirstPage() ? ' disabled' : '' }}"
            onclick="buttonHandler(this)"
            data-key="page"
            data-value="prev"
        >
            < Previous
        </button>

        <span class="pagination-text">Page</span>
        <span class="pagination-text pagination-current-page">{{$currentPage}}</span>
        <span class="pagination-text">of</span>
        <span class="pagination-text pagination-last-page">{{$lastPage}}</span>

        <button
            type="button"
            class="pagination-next{{ $items->hasMorePages() ? '' : ' disabled' }}"
            onclick="buttonHandler(this)"
            data-key="page"
            data-value="next"
        >
            Next >
        </button>
    </div>
</div>

<script src="{{ asset('js/pagination-script.js') }}"></script>

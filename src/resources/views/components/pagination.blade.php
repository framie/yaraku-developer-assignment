<div class="pagination">
    <button
        type="button"
        class="button pagination__prev{{ $items->onFirstPage() ? ' disabled' : '' }}"
        onclick="buttonHandler(this)"
        data-key="page"
        data-value="prev"
    >
        < Previous
    </button>

    <span class="pagination__text">Page</span>
    <span class="pagination__text pagination-current-page">{{$currentPage}}</span>
    <span class="pagination__text">of</span>
    <span class="pagination__text pagination-last-page">{{$lastPage}}</span>

    <button
        type="button"
        class="button pagination__next{{ $items->hasMorePages() ? '' : ' disabled' }}"
        onclick="buttonHandler(this)"
        data-key="page"
        data-value="next"
    >
        Next >
    </button>
</div>

<script src="{{ asset('js/pagination-script.js') }}"></script>

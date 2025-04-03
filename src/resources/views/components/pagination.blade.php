<div class="pagination">
    <button
        type="button"
        class="pagination-prev{{ $items->onFirstPage() ? ' disabled' : '' }}"
        onclick="buttonHandler(this)"
        data-key="page"
        data-value="prev"
    >
        Previous
    </button>

    <span class="pagination-from">{{$from}}</span>
    -
    <span class="pagination-to">{{$to}}</span>
    /
    <span class="pagination-total">{{$items->total()}}</span>

    <button
        type="button"
        class="pagination-next{{ $items->hasMorePages() ? '' : ' disabled' }}"
        onclick="buttonHandler(this)"
        data-key="page"
        data-value="next"
    >
        Next
    </button>
</div>

<script src="{{ asset('js/pagination-script.js') }}"></script>

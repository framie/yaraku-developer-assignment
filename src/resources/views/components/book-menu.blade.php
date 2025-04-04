<div class="menu">
    <div class="menu__left panel">
        @include('components.book-modal', ['type' => 'create'])
        @include('components.book-export')
    </div>
    <div class="menu__right panel">
        @include('components.search', ['items' => $books])
    </div>
</div>

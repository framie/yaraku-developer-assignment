// Contains JS code related to the book-table component.

/**
 * Handles button click events and updates search parameters accordingly in
 * order to retrieve book data based on current search, filter and pagination.
 *
 * @param {HTMLButtonElement} button - The clicked button element.
 * @returns {void}
 */
const buttonHandler = (button) => {
    const urlParams = new URLSearchParams(window.location.search);
    let params = {};
    let key = button.dataset.key;
    let value = button.dataset.value;
    if (key === 'page') {
        const page = +urlParams.get('page') || 1;
        params[key] = value === 'prev' ? page - 1 : page + 1;
    } else if (key === 'sort') {
        params = handleSortButton(button, urlParams);
    } if (key === 'search') {
        const searchInput = document.getElementById('search-input');
        if (searchInput && value === 'reset') searchInput.value = '';
        params['search'] = searchInput?.value;
    }
    updateSearchParams(params);
    refreshBookData();
}

/**
 * Handles sort button logic, toggling the order or activating a new sort column.
 *
 * @param {HTMLElement} button - The clicked sorting button.
 * @param {URLSearchParams} urlParams - The current URL search parameters.
 * @returns {Object} - An object containing key-value pairs to update in the URL.
 */
const handleSortButton = (button, urlParams) => {
    const sortButtonClass = 'button-sort';
    const currentSort = urlParams.get('sort');
    const currentOrder = urlParams.get('order') || 'asc';
    const newSort = button.dataset.value;

    // When clicking the current sort column, toggle the sort order.
    if (currentSort === newSort) {
        const newOrder = currentOrder === 'asc' ? 'desc' : 'asc'
        button.classList.remove(`${ sortButtonClass }--${ currentOrder }`);
        button.classList.add(`${ sortButtonClass }--${ newOrder }`);
        return { 'order': newOrder };

    // Otherwise, change the sort column without affecting the sort order.
    } else {
        const activeClass = `${ sortButtonClass }--active`;
        document.querySelector(`.${ activeClass }`)?.setAttribute('class', sortButtonClass);
        button.classList.add(`${ sortButtonClass }--active`, `${ sortButtonClass }--asc`);
        return { 'sort': newSort, 'order': 'asc' };
    }
};

/**
 * Populates the rows in the books table based on provided data.
 *
 * @param {Object[]} books - Array of objects containing book information.
 * @returns {void}
 */
const populateBookRows = books => {
    const bookList = document.getElementById('book-list');
    if (!bookList) return;
    const buttonClass = 'button-book';
    bookList.innerHTML = '';
    books.forEach(book => {
        const authorName = book.author ? book.author.name : book.author_name;
        const publishDate = book.published_at || '';
        let row = document.createElement('tr');
        let modifyButton = `<button type="button" class="${ buttonClass }--modify" `
            + `onclick="modifyHandler(this)" data-book-id="${ book.id }" `
            + `data-title="${ book.title }" data-author-name="${ authorName }" `
            + `data-publish-date="${ publishDate }">Modify</button>`;
        let deleteButton = `<button type="button" class="${ buttonClass }--delete" `
            + `onclick="deleteHandler(this)" data-book-id="${ book.id }">Delete</button>`;
        row.classList.add('book-row');
        row.innerHTML = `
            <td>${ book.title }</td>
            <td>${ authorName }</td>
            <td>${ publishDate }</td>
            <td>${ modifyButton }</td>
            <td>${ deleteButton }</td>
            `;
        bookList.appendChild(row);
    });
}

/**
 * Fetches and updates the book data from the DB based on the URL query parameters.
 * Will also refresh the pagination component with new data if necessary.
 *
 * @returns {void}
 */
const refreshBookData = () => {
    const tableElement = document.getElementById('book-table');
    if (!tableElement) return;
    const routeUrl = tableElement.dataset.url;
    const urlParams = new URLSearchParams(window.location.search);
    const sort = urlParams.get('sort');
    const order = urlParams.get('order');
    const search = urlParams.get('search') || '';
    const page = urlParams.get('page');
    const path = `${routeUrl}?sort=${sort}&order=${order}&search=${search}&page=${page}`;

    ajax(path, 'GET')
        .then(({ _, body }) => {
            const {data, from, to, total, next_page_url} = body.books;
            populateBookRows(data);
            if (typeof refreshPagination !== 'function') return;
            refreshPagination(from, to, total, next_page_url);
        })
        .catch(error => console.error('Error when retrieving books:', error));
}

/**
 * Handles logic when a book delete button is clicked.
 *
 * @param {HTMLButtonElement} button - Button where handler function was called.
 * @returns {void}
 */
const deleteHandler = button => {
    if (button.innerText === 'Delete') {
        button.innerText = 'Confirm';
        setTimeout(() => button.innerText = 'Delete', 2000);
        return;
    }
    const bookId = button.dataset.bookId;
    const tableElement = document.getElementById('book-table');
    if (!bookId || !tableElement) return;
    const routeUrl = tableElement.dataset.url;
    const token = tableElement.querySelector('input[name="_token"]').value;
    const headers = {'Content-Type': 'application/json'};
    ajax(`${ routeUrl }/${ bookId }`, 'DELETE', token, {}, headers)
        .then(refreshBookData);
}

/**
 * Handles logic when a book modify button is clicked.
 *
 * @param {HTMLButtonElement} button - Button where handler function was called.
 * @returns {void}
 */
const modifyHandler = button => {
    const bookId = button.dataset.bookId;
    const title = button.dataset.title;
    const authorName = button.dataset.authorName;
    const publishDate = button.dataset.publishDate;
    if (typeof populateBookModifyModal !== 'function') return;
    populateBookModifyModal(bookId, title, authorName, publishDate);
}

// Ensure that data is reloaded when the browser back/forward buttons are pressed.
window.addEventListener('popstate', refreshBookData);

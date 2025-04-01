// Contains JS code related to the book-table component.

/**
 * Updates search parameters and pushes the new URL into browser history.
 *
 * @param {Object} params - An object containing key-value pairs to update in the URL.
 * @returns {void}
 */
const updateSearchParams = (params) => {
    const url = new URL(window.location);
    Object.entries(params).forEach(([key, value]) => {
        url.searchParams.set(key, value);
    });
    window.history.pushState({}, '', url);
};

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
    const sortButtons = document.querySelectorAll(`.${sortButtonClass}`);
    const currentSort = urlParams.get('sort');
    const currentOrder = urlParams.get('order') || 'asc';
    const newSort = button.dataset.value;

    // When clicking the current sort column, toggle the sort order.
    if (currentSort === newSort) {
        const newOrder = currentOrder === 'asc' ? 'desc' : 'asc'
        sortButtons.forEach(sortButton => {
            sortButton.classList.remove(`${sortButtonClass}--${currentOrder}`);
            sortButton.classList.add(`${sortButtonClass}--${newOrder}`);
        });
        return { 'order': newOrder };

    // Otherwise, change the sort column without affecting the sort order.
    } else {
        const activeClass = `${sortButtonClass}--active`;
        sortButtons.forEach(sortButton => {
            sortButton.classList.remove(activeClass, `${sortButtonClass}--desc`);
            sortButton.classList.add(`${sortButtonClass}--asc`);
        });
        button.classList.add(activeClass);
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
        let row = document.createElement('tr');
        let modifyButton = `<button class="${buttonClass}--modify"`;
        modifyButton += ` onclick="modifyHandler(this)">Modify</button>`;
        let deleteButton = `<button class="${buttonClass}--delete"`;
        deleteButton += ` onclick="deleteHandler(this)">Delete</button>`;
        row.classList.add('book-row');
        row.setAttribute('data-book-id', book.id);
        row.innerHTML = `
            <td>${book.title}</td>
            <td>${book.author ? book.author.name : book.name}</td>
            <td>${book.published_at || ''}</td>
            <td>${modifyButton}</td>
            <td>${deleteButton}</td>
            `;
        bookList.appendChild(row);
    });
}

/**
 * Updates the text content of an HTML element selected by a CSS selector.
 *
 * @param {string} selector - The CSS selector of the element.
 * @param {string} text - The text content to set.
 * @returns {void}
 */
const updateElementText = (selector, text) => {
    const element = document.querySelector(selector);
    if (element && text) element.innerText = text;
}

/**
 * Updates the pagination UI with new values.
 *
 * @param {number} from - The starting item number for the current page.
 * @param {number} to - The ending item number for the current page.
 * @param {number} total - The total number of items.
 * @param {string|null} nextPageUrl - The URL for the next page, otherwise null.
 * @returns {void}
 */
const refreshPagination = (from, to, total, nextPageUrl = None) => {
    updateElementText('.pagination-from', from);
    updateElementText('.pagination-to', to);
    updateElementText('.pagination-total', total);
    const nextPageElement = document.querySelector('.pagination-next');
    if (nextPageUrl && nextPageElement) nextPageElement.classList.remove('disabled');
}

/**
 * Fetches and updates the book data from the DB based on the URL query parameters.
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
    const page = urlParams.get('page');

    fetch(`${routeUrl}?sort=${sort}&order=${order}&page=${page}`, {
        method: 'GET',
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(response => response.json())
    .then(json => {
        const {data, from, to, total, next_page_url} = json.books;
        populateBookRows(data);
        refreshPagination(from, to, total, next_page_url);
    })
    .catch(error => console.error('Error when refreshing book data:', error));
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
    const bookId = button.parentElement.parentElement.dataset.bookId;
    const tableElement = document.getElementById('book-table');
    if (!tableElement || !bookId) return;
    const routeUrl = tableElement.dataset.url;
    const token = tableElement.querySelector('input[name="_token"]').value;
    fetch(`${routeUrl}/${bookId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(refreshBookData)
    .catch(error => console.error('Error when deleting book:', error));
}

// Ensure that data is reloaded when the browser back/forward buttons are pressed.
window.addEventListener("popstate", refreshBookData);

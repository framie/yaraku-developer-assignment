// Contains JS code related to the pagination component.

/**
 * Updates the pagination UI with new values.
 *
 * @param {number} currentPage - The current page number.
 * @param {number} lastPage - The last page number (i.e. the total number of pages).
 * @param {string|null} nextPageUrl - The URL for the next page, otherwise null.
 * @returns {void}
 */
const refreshPagination = (currentPage, lastPage, nextPageUrl = None) => {
    updateElementText('.pagination-current-page', currentPage);
    updateElementText('.pagination-last-page', lastPage);
    const prevPageElement = document.querySelector('.pagination__prev');
    if (prevPageElement) {
        if (currentPage > 1) prevPageElement.classList.remove('disabled');
        else prevPageElement.classList.add('disabled');
    }
    const nextPageElement = document.querySelector('.pagination__next');
    if (nextPageElement) {
        if (nextPageUrl) nextPageElement.classList.remove('disabled');
        else nextPageElement.classList.add('disabled');
    }
}

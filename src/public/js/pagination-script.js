// Contains JS code related to the pagination component.

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

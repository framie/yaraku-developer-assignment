// Contains JS code related to the book-modal component when type == 'modify'.

/**
 * Populates the book modify modal with the specified data.
 *
 * @param {HTMLButtonElement} button - Button where handler function was called.
 * @returns {void}
 */
const populateBookModifyModal = (bookId, title, authorName, publishDate = '') => {
    document.getElementById('book-modify-id').value = bookId;
    document.getElementById('book-modify-title').value = title;
    document.getElementById('book-modify-author-name').value = authorName;
    document.getElementById('book-modify-publish-date').value = publishDate;
}

/**
 * Submits the form to modify an existing book.
 * Will also refresh the books table with the new changes.
 *
 * @returns {void}
 */
const submitBookModifyForm = () => {
    const form = document.getElementById('book-modify-form');
    const bookId = form.querySelector('#book-modify-id').value;
    const url = `${ form.getAttribute('action') }/${ bookId }`;
    submitForm(form, url).then(() => {
        typeof refreshBookData === 'function' && refreshBookData();
    });
}

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
    const messageElement = document.querySelector('#book-modify-modal .message');
    if (messageElement) messageElement.innerText = '';
}

/**
 * Submits the form to modify an existing book.
 * Will also refresh the books table with the new changes.
 *
 * @returns {void}
 */
const submitBookModifyForm = () => {
    const formElement = document.getElementById('book-modify-form');
    const bookId = formElement.querySelector('#book-modify-id').value;
    const url = `${ formElement.getAttribute('action') }/${ bookId }`;
    submitForm(formElement, url).then(success => {
        if (!success) return;
        typeof refreshBookData === 'function' && refreshBookData(false);
    });
}

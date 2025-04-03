// Contains JS code related to the book-modal component when type == 'create'.

/**
 * Submits the form to create a new book and stores it in the database.
 * Will also refresh the books table with the newly stored data.
 *
 * @returns {void}
 */
const submitBookCreateForm = () => {
    const form = document.getElementById('book-create-form');
    const url = form.getAttribute('action');
    submitForm(form, url).then(() => {
        typeof refreshBookData === 'function' && refreshBookData();
    });
}

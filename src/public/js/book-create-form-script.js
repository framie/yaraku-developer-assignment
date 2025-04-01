// Contains JS code related to the book-create-form component.

/**
 * Submits the form to create a new book and stores it in the database.
 * Will also refresh the books table with the newly stored data.
 *
 * @returns {boolean} returns false to prevent form from submitting.
 */
const submitBookCreateForm = () => {
    const bookForm = document.getElementById('book-create-form');
    if (!bookForm) return false;
    const routeUrl = bookForm.getAttribute('action');
    const token = bookForm.querySelector('input[name="_token"]').value;
    const message = bookForm.querySelector('#book-create-message');
    message.textContent = '';

    fetch(routeUrl, {
        method: 'POST',
        body: new FormData(bookForm),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
        }
    })
    .then(response => response.json().then(data => ({ status: response.status, body: data })))
    .then(({ status, body }) => {
        if (status === 422) {
            Object.values(body.errors).forEach(error => {
                message.textContent += `${error} `;
            })
        } else {
            message.textContent = body.message;
            bookForm.reset();
            refreshBookData();
        }
    })
    .catch(error => console.error('Error when submitting book create form:', error));
    return false;
}

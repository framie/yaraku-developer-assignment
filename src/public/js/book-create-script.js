// Contains JS code related to the book-modal component when type == 'create'.

/**
 * Submits the form to create a new book and stores it in the database.
 * Will also refresh the books table with the newly stored data.
 *
 * @returns {void}
 */
const submitBookCreateForm = () => {
    setLoadingState(true);
    const formElement = document.getElementById('book-create-form');
    const url = formElement.getAttribute('action');
    submitForm(formElement, url).then(success => {
        setLoadingState(false);
        if (!success) return;
        formElement.reset();
        typeof refreshBookData === 'function' && refreshBookData(false);
    });
}

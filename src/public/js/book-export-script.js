// Contains JS code related to the book-export component.

/**
 * Handles the logic for the export form by retrieving the user input format
 * and type values to trigger the respective file download.
 *
 * @returns {void}
 */
const exportHandler = () => {
    const formElement = document.getElementById('book-export-form');
    const data = new FormData(formElement);
    const format = data.get('format');
    const type = data.get('type');
    const url = `${ formElement.getAttribute('action') }/export/${ format }/${ type }`;
    const messageElement = formElement.querySelector('.message');
    // If form message element exists, reset it.
    if (messageElement) {
        messageElement.textContent = '';
        messageElement.classList.remove('error');
    }
    setLoadingState(true);
    fetch(url, { method: 'GET' })
        .then(response => {
            setLoadingState(false);
            if (!response.ok) {
                return response.json().then(body => {
                    messageElement.classList.add('error');
                    Object.values(body.errors).forEach(error => {
                        messageElement.textContent += `${ error } `;
                    });
                });
            }
            // If data exists, proceed with download
            window.location.href = url;
        })
        .catch(error => {
            console.error('Error when exporting data:', error);
        });
}

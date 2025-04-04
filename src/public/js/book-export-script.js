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
    window.location.href = url;
}

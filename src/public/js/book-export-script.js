// Contains JS code related to the book-export component.

/**
 * Handles the logic for the export form by retrieving the user input format
 * and type values to trigger the respective file download.
 *
 * @returns {void}
 */
const exportHandler = () => {
    const form = document.getElementById('book-export-form');
    const data = new FormData(form);
    const format = data.get('format');
    const type = data.get('type');
    window.location.href = `${ form.getAttribute('action') }/export/${ format }/${ type }`;
}

// Contains general helper functions that aren't specific to a specific function.

/**
 * Updates the text content of an HTML element selected by a CSS selector.
 *
 * @param {string} selector - The CSS selector of the element.
 * @param {string} text - The text content to set.
 * @returns {void}
 */
const updateElementText = (selector, text) => {
    const element = document.querySelector(selector);
    if (element) element.innerText = text;
}

/**
 * Updates search parameters and pushes the new URL into browser history.
 *
 * @param {Object} params - An object containing key-value pairs to update in the URL.
 * @returns {void}
 */
const updateSearchParams = (params) => {
    const url = new URL(window.location);
    Object.entries(params).forEach(([key, value]) => {
        if (!value) return url.searchParams.delete(key);
        url.searchParams.set(key, value);
    });
    window.history.pushState({}, '', url);
};

/**
 * Helper function for sending a fetch request.
 *
 * @param {string} path - The URL endpoint for the request.
 * @param {string} token - The CSRF token for authentication.
 * @param {string} method - The HTTP method
 * @param {Object|FormData} body - The request body (optional, for POST/PUT).
 * @param {Object} headers - Additional headers to include in the request.
 * @returns {Promise<{ status: number, body: any }>} - A promise resolving to an object.
 */
const ajax = async (path, method, token, body = {}, headers = {}) => {
    const options = {
        method,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            ...(token && {'X-CSRF-TOKEN': token}),
            ...headers
        },
        ...(method !== 'GET' && body && { body })
    };

    const response = await fetch(path, options);
    const data = await response.json();
    return { status: response.status, body: data };
};

/**
 * Helper function to submits a form by sending AJAX request via specified method.
 *
 * @returns {void}
 */
const submitForm = (form, url) => {
    const token = form.querySelector('input[name="_token"]').value;
    const message = form.querySelector('.message');
    if (message) message.textContent = '';

    return ajax(url, 'POST', token, new FormData(form))
        .then(({ status, body }) => {
            if (status !== 200) {
                Object.values(body.errors).forEach(error => {
                    if (message) message.textContent += `${ error } `;
                })
            } else {
                if (message) message.textContent = body.message;
                form.reset();
            }
        })
        .catch(error => console.error('Error when submitting form:', error));
}

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

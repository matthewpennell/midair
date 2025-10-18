/**
 * DOM utility functions
 */

export function createElement(tag, className, content = '') {
    const element = document.createElement(tag);
    if (className) element.className = className;
    if (content) element.textContent = content;
    return element;
}

export function ensureElementExists(selector, parent = document) {
    const element = parent.querySelector(selector);
    if (!element) {
        console.warn(`Element not found: ${selector}`);
    }
    return element;
}

export function onEvent(element, event, callback, options) {
    element.addEventListener(event, callback, options);
    return () => element.removeEventListener(event, callback, options);
}

export function onDocumentReady(callback) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback);
    } else {
        callback();
    }
}

export function createNotificationWrapper() {
    const wrapper = document.createElement('div');
    wrapper.id = 'notification-wrap';
    document.body.appendChild(wrapper);
    return wrapper;
}

// Main entry point for the application
import { setupListContainer } from './modules/layout.js';
import { highlightSearchTerms } from './modules/search.js';
import { lastFmService } from './modules/lastfm.js';
import { notificationManager } from './modules/notifications.js';

// Initialize layout functionality
setupListContainer();

// Initialize search highlighting if on search results page
if (document.getElementById('query')) {
    highlightSearchTerms();
}

// Initialize Last.fm integration and notifications
const isNotBackNavigation = window.performance?.navigation?.type !== 2;
if (isNotBackNavigation) {
    // Show Bluesky notification if available
    notificationManager.showBluesky();
    // Initialize Last.fm polling
    lastFmService.startPolling();
}

// Initialize mobile navigation
document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.querySelector('.mobile-nav__button');
    const nav = document.querySelector('.primary');
    function toggleHamburger() {
        navToggle.classList.toggle('active');
        nav.classList.toggle('active');
    }
    navToggle.addEventListener('click', toggleHamburger);
});

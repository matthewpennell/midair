import { createElement } from './domUtils.js';

class NotificationManager {
    constructor() {
        this.currentTrackId = null;
        this.notificationWrapper = null;
        this.dismissedTracks = new Set(JSON.parse(localStorage.getItem('dismissedTracks') || '[]'));
        this.initialize();
    }

    initialize() {
        this.notificationWrapper = document.getElementById('notification-wrap');
        if (!this.notificationWrapper) {
            this.notificationWrapper = document.createElement('div');
            this.notificationWrapper.id = 'notification-wrap';
            document.body.appendChild(this.notificationWrapper);
        }
    }

    showNowPlaying(track) {
        const trackId = `${track.artist['#text']}:${track.name}`;
        
        // Skip if same track is already playing or if this track was dismissed
        if (trackId === this.currentTrackId || this.dismissedTracks.has(trackId)) {
            return;
        }

        this.currentTrackId = trackId;
        // Remove from dismissed tracks when a new track starts playing
        this.dismissedTracks.delete(trackId);
        this._updateDismissedTracks();
        
        this._showNotification(this._createNowPlayingNotification(track));
    }

    showBluesky() {
        const blueskyElement = document.getElementById('latest-bluesky');
        // Check if element exists and has content, even if it's hidden
        if (!blueskyElement || !blueskyElement.textContent.trim()) return;
        
        // Create notification with the text content (safer than innerHTML)
        this._showNotification(this._createBlueskyNotification(blueskyElement.innerHTML));
    }

    _showNotification(notificationElement) {
        // Remove any existing notification of the exact same type
        const notificationType = notificationElement.className.split(' ')[1];
        const existingNotifications = this.notificationWrapper.querySelectorAll(`.${notificationType}`);
        
        // Remove all existing notifications of this type
        existingNotifications.forEach(notif => notif.remove());

        // Add the new notification
        this.notificationWrapper.appendChild(notificationElement);
        
        // Trigger animation
        setTimeout(() => notificationElement.classList.add('visible'), 100);
        
        // Auto-dismiss after 10 seconds
        const dismissTimer = setTimeout(() => this._dismissNotification(notificationElement), 10000);
        
        // Click to dismiss
        notificationElement.addEventListener('click', () => {
            clearTimeout(dismissTimer);
            this._dismissNotification(notificationElement);
        });
    }

    _dismissNotification(notification) {
        notification.classList.remove('visible');
        
        // If this is a now playing notification, add to dismissed tracks
        if (notification.classList.contains('now-playing-notification') && this.currentTrackId) {
            this.dismissedTracks.add(this.currentTrackId);
            this._updateDismissedTracks();
        }
        
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
    
    _updateDismissedTracks() {
        // Keep only the 100 most recent dismissed tracks to prevent localStorage from growing too large
        const dismissedArray = Array.from(this.dismissedTracks).slice(-100);
        localStorage.setItem('dismissedTracks', JSON.stringify(dismissedArray));
    }

    _createNowPlayingNotification(track) {
        const notification = createElement('div', 'notification now-playing-notification');
        
        // Album art
        const img = createElement('img');
        img.src = track.image[3]?.['#text'] || '';
        img.alt = `${track.name} by ${track.artist?.['#text'] || 'Unknown Artist'}`;
        notification.appendChild(img);
      
        // Info container
        const info = createElement('div', 'now-playing-info');
      
        // Notification content
        info.appendChild(createElement('div', 'now-playing-label', 'Now Playing'));
        info.appendChild(createElement('h3', 'now-playing-title', track.name));
        info.appendChild(createElement('p', 'now-playing-artist', track.artist?.['#text'] || 'Unknown Artist'));
      
        notification.appendChild(info);
        return notification;
    }

    _createBlueskyNotification(content) {
        const notification = createElement('div', 'notification bluesky-notification');
        const info = createElement('div', 'now-playing-info');
        
        info.appendChild(createElement('div', 'now-playing-label', 'Latest on Bluesky'));
        
        const contentDiv = createElement('div', 'now-playing-artist');
        contentDiv.innerHTML = content;
        info.appendChild(contentDiv);
        
        notification.appendChild(info);
        return notification;
    }
}

export const notificationManager = new NotificationManager();

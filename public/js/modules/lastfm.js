import { notificationManager } from './notifications.js';

const LAST_FM_API_KEY = 'eae3e31423356ac400ac5c429a5b855e';
const LAST_FM_USER = 'Watchmaker';
const POLLING_INTERVAL = 180000; // 3 minutes

class LastFmService {
    constructor() {
        this.lastFetchedTrack = null;
    }

    async fetchNowPlaying() {
        try {
            const url = `https://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=${LAST_FM_USER}&api_key=${LAST_FM_API_KEY}&format=json&limit=1`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            const track = data.recenttracks?.track?.[0];
            
            if (track?.['@attr']?.nowplaying === 'true') {
                // Only show notification if it's a different track
                const trackId = `${track.artist['#text']}:${track.name}`;
                if (trackId !== this.lastFetchedTrack) {
                    this.lastFetchedTrack = trackId;
                    notificationManager.showNowPlaying(track);
                }
            } else {
                this.lastFetchedTrack = null;
            }
        } catch (error) {
            console.error('Error fetching Last.fm data:', error);
        }
    }

    startPolling() {
        // Initial fetch (unless Back button use detected)
        if (window.performance?.navigation?.type !== 2) {
            this.fetchNowPlaying();
        }
        
        // Set up polling
        return setInterval(() => this.fetchNowPlaying(), POLLING_INTERVAL);
    }
}

export const lastFmService = new LastFmService();

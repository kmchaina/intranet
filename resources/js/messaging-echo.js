import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const broadcaster = import.meta.env.VITE_BROADCAST_DRIVER || 'pusher';
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;

if (broadcaster === 'pusher' && pusherKey) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: pusherKey,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
        wsHost: import.meta.env.VITE_PUSHER_HOST || `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
        wsPort: import.meta.env.VITE_PUSHER_PORT || 80,
        wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
        forceTLS: (import.meta.env.VITE_PUSHER_SCHEME || 'https') === 'https',
        enabledTransports: ['ws','wss'],
        disableStats: true,
    });
} else {
    // Fallback to no-op object to avoid errors if Echo not configured
    console.log('Echo not configured (missing Pusher key or disabled)');
    window.Echo = { private(){ return { listen(){ return this; } } } };
}

window.__echoReady = true;
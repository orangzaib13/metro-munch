import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,       // ✅ use import.meta.env
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // ✅ use import.meta.env
    forceTLS: true
});

console.log('Pusher Key:', import.meta.env.VITE_PUSHER_APP_KEY);
console.log('Pusher Cluster:', import.meta.env.VITE_PUSHER_APP_CLUSTER);

// Service Worker for Offline Functionality
const CACHE_NAME = 'todo-app-v3';
const urlsToCache = [
    '/',
    '/index.php',
    '/todo.php',
    '/kanban.php',
    '/css/style.css',
    '/js/script.js',
    '/js/jquery-3.2.1.min.js',
    '/img/f.png',
    '/img/Ellipsis.gif'
];

// Install event
self.addEventListener('install', event => {
    console.log('Service worker installing');
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

// Fetch event
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Return cached version or fetch from network
                return response || fetch(event.request);
            }
        )
    );
});

// Activate event
self.addEventListener('activate', event => {
    console.log('Service worker activating');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            console.log('Service worker activated and old caches cleared');
            return self.clients.claim();
        })
    );
});

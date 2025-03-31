const CACHE_NAME = 'saegis-address-book-v1';
const urlsToCache = [
    '/',
    '/index.php',
    '/login.php',
    '/register.php',
    '/forgot-password.php',
    '/reset-password.php',
    '/dashboard.php',
    '/admin-dashboard.php',
    '/css/styles.css',
    '/js/app.js',
    '/images/logo.png',
    '/images/computing-icon.png',
    '/images/management-icon.png',
    '/images/finance-icon.png',
    '/images/marketing-icon.png',
    '/images/registrar-icon.png',
    '/images/examination-icon.png',
    '/images/library-icon.png',
    '/images/study-abroad-icon.png',
    '/images/menu-icon.png'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
    );
});
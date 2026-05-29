const CACHE_NAME = 'awwal-lab-v2';
const urlsToCache = [
  '/css/style.css?v=2',
  '/css/vendors_css.css?v=2',
  '/css/skin_color.css?v=2',
  '/js/template.js',
  '/js/vendors.min.js',
  '/images/logo-pwa.png',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  // Skip cross-origin requests and non-GET requests
  if (event.request.method !== 'GET') return;

  // Only intercept static assets, let navigation/HTML handle redirects normally
  const url = new URL(event.request.url);
  const isAsset = url.pathname.match(/\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot)$/) || 
                  url.host.includes('cdnjs.cloudflare.com');

  if (!isAsset) return;

  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request).catch(err => {
          console.warn('ServiceWorker fetch failed:', event.request.url, err);
          return null; // Return null to let the browser handle the failure naturally
        });
      })
  );
});

self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

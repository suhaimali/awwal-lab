const CACHE_NAME = 'awwal-lab-v4';
const urlsToCache = [
  '/',
  '/favicon.svg',
  '/css/auth.css',
  '/js/auth.js',
  'https://code.jquery.com/jquery-3.7.1.min.js',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
  'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
  'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
  'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js',
  'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
  'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css',
  'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
  'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
  'https://cdn.jsdelivr.net/npm/sweetalert2@11',
  'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
  'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js',
  'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js'
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

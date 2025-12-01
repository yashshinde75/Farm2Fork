const CACHE_NAME = "farm2fork-v1";

const urlsToCache = [
  "/",
  "/index.php",
  "/about.php",
  "/how-it-works.php",
  "/products.php",
  "/cart.php",
  "/contact.php",
  "/assets/css/style.css"
];

self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(urlsToCache);
    })
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});

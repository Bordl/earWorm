window.BASE_DIR = `https://${document.location.hostname}`
window.API = `https://aiyura.dev-shm.net/api`
window.WEB = `https://aiyura.dev-shm.net`

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register(`${BASE_DIR}/sw.js`)
        .then(() => console.log('Service worker is loaded'));
    });
}
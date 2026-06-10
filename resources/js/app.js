import './bootstrap';

window.Echo.channel('data-updates')
    .listen('DataChanged', (e) => {
        console.log('Realtime Update Received:', e);
        // TODO: Add your logic to refresh the DOM or fetch data here
    });

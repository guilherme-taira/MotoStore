self.addEventListener('push', function(event) {
    const data = event.data.json();
    const options = {
        body: data.body,
        icon: '/path/to/icon.png', // Opcional: adicione um ícone para a notificação
        badge: '/path/to/badge.png' // Opcional: adicione um badge para a notificação
    };
    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

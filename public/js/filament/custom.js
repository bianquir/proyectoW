document.addEventListener('livewire:load', function () {
    const chatMessages = document.getElementById('chat-messages');
    let oldScrollHeight = chatMessages.scrollHeight;
    let scrollTimeout;

    // Detectar el scroll en el div de mensajes
    chatMessages.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            if (chatMessages.scrollTop === 0) {
                window.livewire.emit('loadMoreMessages');
            }
        }, 150);
    });

    document.addEventListener('livewire:load', function () {
        Livewire.on('scrollToBottom', function () {
            const chatMessages = document.getElementById('chat-messages'); // Asegúrate de que este sea el ID correcto
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight; // Desplaza al fondo
            }
        });
    });
    

    // Desplazar hacia abajo al cargar el chat
    window.addEventListener('scroll-to-bottom', function () {
        chatMessages.scrollTop = chatMessages.scrollHeight; // Desplazar al fondo
    });
});

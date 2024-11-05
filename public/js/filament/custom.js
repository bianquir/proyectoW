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

function closeModal() {
    document.querySelector('.modal-overlay').classList.add('close');
    document.querySelector('.modal-content').classList.add('close');

    setTimeout(() => {
        document.querySelector('.modal-overlay').style.display = 'none';
    }, 500); // Duración de la animación de salida
}

function openModal() {
    const overlay = document.querySelector('.modal-overlay');
    overlay.style.display = 'flex';

    // Remueve la clase "close" si se había añadido antes
    overlay.classList.remove('close');
    document.querySelector('.modal-content').classList.remove('close');
}

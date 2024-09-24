<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\Customer;

class ChatView extends Component
{
    public $customers; 
    public $selectedCustomer; 
    public $messages;
    public $newMessage; 
    public $loadingMore = false;

    public function mount()
    {
        $this->customers = Customer::all();

        // Selecciona el primer cliente como predeterminado
        if ($this->customers->isNotEmpty()) {
            $this->selectedCustomer = $this->customers->first()->id;
            $this->loadMessages(); // Cargar mensajes del cliente seleccionado
        }
    }

    public function loadMessages()
    {
        $this->messages = Message::where('customer_id', $this->selectedCustomer)
                        ->orderBy('timestamp', 'desc')
                        ->take(8)
                        ->get()
                        ->sortBy('timestamp');

    }

    public function loadMoreMessages()
    {
        $this->loadingMore = true;
        $currentMessageCount = count($this->messages);
        $moreMessages = Message::where('customer_id', $this->selectedCustomer)
                               ->orderBy('timestamp', 'desc')
                               ->skip($currentMessageCount) // Saltar los mensajes que ya cargaste
                               ->take(8) // Cargar 50 más
                               ->get()
                               ->sortBy('timestamp'); // Reordenar cronológicamente

        // Agregar los mensajes nuevos a la colección existente
        $this->messages = $moreMessages->merge($this->messages);
        $this->loadingMore = false;

        if ($moreMessages->isEmpty()) {
            $this->loadingMore = false;
            return;
        }
        
    }

    public function onScroll()
    {
        // Detectar si estamos cerca del principio de la lista de mensajes
        $scrollPosition = request()->input('scrollPosition');
        
        // Si estamos cerca del principio, cargar más mensajes
        if ($scrollPosition < 100) {
            $this->loadMoreMessages();
        }
    }


    public function selectCustomer($customerId)
    {
        // Cambia el cliente seleccionado y recarga los mensajes
        $this->selectedCustomer = $customerId;
        $this->loadMessages();
    }

    public function sendMessage()
    {
        // Crear un nuevo mensaje para el cliente seleccionado
        $message = Message::create([
            'customer_id' => $this->selectedCustomer,
            'message' => $this->newMessage,
            'message_type' => 'text',
            'direction' => 'outbound',
            'status' => 'sent',
            'timestamp' => now(),
        ]);

        // Añadir el nuevo mensaje a la lista
        $this->messages->prepend($message);

        // Limpiar el campo de nuevo mensaje
        $this->newMessage = '';
        $this->dispatchBrowserEvent('message-sent');
    }

    public function render()
    {
        return view('livewire.chat-view');
    }
}

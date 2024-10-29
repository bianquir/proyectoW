<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\Customer;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ChatView extends Component
{
    public $customers; 
    public $selectedCustomer; 
    public $messages;
    public $newMessage; 
    public $loadingMore = false;
    public $showModal;
    public $tags= [];
    public $selectedTags = []; 
    public $customer;

    public function mount()
    {
        // Obtener el último mensaje de cada cliente
        $lastMessages = Message::select('customer_id', DB::raw('MAX(timestamp) as last_timestamp'))
            ->groupBy('customer_id')
            ->get();
        
        // Verificar si hay mensajes antes de proceder
        if ($lastMessages->isEmpty()) {
            $this->customers = collect(); // Crear una colección vacía para clientes
            return; // Terminar aquí si no hay mensajes
        }
    
        // Obtener los clientes y sus últimos mensajes
        $this->customers = Customer::with(['messages' => function ($query) {
            $query->orderBy('timestamp', 'desc')->limit(1); // Obtener solo el último mensaje
        }, 'tags'])
        ->whereIn('id', $lastMessages->pluck('customer_id')) // Filtrar solo los clientes que tienen mensajes
        ->get()
        ->sortByDesc(function ($customer) use ($lastMessages) {
            $lastMessage = $lastMessages->firstWhere('customer_id', $customer->id);
            return $lastMessage ? $lastMessage->last_timestamp : null; // Ordenar clientes por el último mensaje
        });
    
        $this->selectedCustomer = null; // Ningún cliente seleccionado inicialmente
    }

    public function formatMessageDate($timestamp)
    {
        $messageDate = Carbon::parse($timestamp);
        $now = Carbon::now();

        // Si es hoy
        if ($messageDate->isToday()) {
            return 'Today';
        }

        // Si es en esta misma semana
        if ($messageDate->isSameWeek($now)) {
            return $messageDate->translatedFormat('l'); // Lunes, Martes, etc.
        }

        // Si es más antiguo que una semana, mostrar la fecha completa
        return $messageDate->translatedFormat('d M Y');
    }


    public function loadMessages()
    {
        if ($this->selectedCustomer) {
        $this->messages = Message::where('customer_id', $this->selectedCustomer)
                        ->orderBy('timestamp', 'desc')
                        ->take(8)
                        ->get()
                        ->sortBy('timestamp');
        } else {
                $this->messages = collect(); // Si no hay cliente seleccionado, colección vacía
            }
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
        if ($scrollPosition < 8) {
            $this->loadMoreMessages();
        }
    }


    public function selectCustomer($customerId)
    {
        // Cambia el cliente seleccionado y recarga los mensajes
        $this->selectedCustomer = $customerId;
        $this->loadMessages();
        $this->loadCustomerTags();
    }

    public function sendMessage($customerId)
    {
        $customer = Customer::find($customerId);


        $whatsapp_api_url = env('WHATSAPP_API_URL');
        $whatsapp_api_version = env('WHATSAPP_API_VERSION');
        $whatsapp_api_number_id = env('WHATSAPP_PHONE_NUMBER_ID');
        $access_token = env('WHATSAPP_ACCESS_TOKEN');

        $whatsapp_full_url = $whatsapp_api_url . '/' . $whatsapp_api_version . '/' . $whatsapp_api_number_id . '/messages'; 

        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $customer->wa_id,
            'type' => 'text',
            'text' => [
                'body' => $this->newMessage,
            ],
        ];


        $response = Http::withToken($access_token)->post($whatsapp_full_url, $data);


        if ($response->successful()) {
            // Limpiar el formulario después de enviar
            $this->newMessage = '';

            $this->loadMessages();
        } else {
            // Mostrar mensaje de error
            session()->flash('error', 'Error al enviar el mensaje.');
        }
    

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
        // $this->dispatchBrowserEvent('message-sent');
        $this->loadMessages();
    }

    public function openModal()
    {
        $this->tags = Tag::all();  // Cargar todas las etiquetas
        $this->selectedTags = [];  // Limpiar las etiquetas seleccionadas
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveTags()
    {
        // Obtener el cliente seleccionado
        $customer = Customer::find($this->selectedCustomer);

        // Asignar las etiquetas seleccionadas al cliente
        // El método sync manejará automáticamente los timestamps
        $customer->tags()->sync($this->selectedTags);

        // Cerrar el modal después de guardar
        $this->showModal = false;

        // Establecer el mensaje de éxito en la sesión
        session()->flash('success', 'Etiquetas asignadas con éxito.');
    }

    public function loadCustomerTags()
    {
        // Cargar el cliente seleccionado con sus etiquetas
        $this->customer = Customer::with('tags')->find($this->selectedCustomer);
    }

    public function render()
    {
        return view('livewire.chat-view');
    }
}

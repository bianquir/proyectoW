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
    public $showTagModal = false;
    public $selectedTag = null;
    public $selectedCustomerForTag = null; // Cliente asociado a la etiqueta seleccionada
    public $showConfirmModal = false;
    public $showCreateModal = false;
    public $createTagModal = false;
    public $newTag = [
        'name_tag' => '',
        'description' => '',
        'color' => ''
    ];
    public $showDataModal = false;
    
    public function mount()
    {
        // Obtener el último mensaje de cada cliente
        $lastMessages = Message::select('customer_id', 'message', 'direction', 'timestamp')
        ->whereIn('timestamp', function ($query) {
            $query->select(DB::raw('MAX(timestamp)'))
                ->from('messages')
                ->groupBy('customer_id');
        })
        ->get();
    
        if ($lastMessages->isEmpty()) {
            $this->customers = collect();
            return;
        }
    
        // Cargar clientes y sus últimos mensajes
        $this->customers = Customer::with('tags')
            ->whereIn('id', $lastMessages->pluck('customer_id'))
            ->get()
            ->each(function ($customer) use ($lastMessages) {
                $lastMessage = $lastMessages->firstWhere('customer_id', $customer->id);
                if ($lastMessage) {
                    $customer->lastMessage = $lastMessage;
                }
            })
            ->sortByDesc(fn($customer) => $customer->lastMessage->timestamp ?? null);
    
        // Inicializa variables adicionales si es necesario
        $this->selectedCustomer = null;
    }
    
    public function formatMessageDate($timestamp)
    {
        $messageDate = Carbon::parse($timestamp);
        $now = Carbon::now();
    
        // Si es hoy
        if ($messageDate->isToday()) {
            return 'Hoy';
        }
    
        // Si es en esta misma semana
        if ($messageDate->isSameWeek($now)) {
            return $messageDate->translatedFormat('l'); // Lunes, Martes, etc.
        }
    
        // Si es más antiguo que una semana, mostrar la fecha completa
        return $messageDate->translatedFormat('d M Y'); // d M Y ya estará en español
    }


    public function loadMessages()
    {
        if ($this->selectedCustomer) {
            // Cargar los mensajes del cliente seleccionado
            $this->messages = Message::where('customer_id', $this->selectedCustomer)
                            ->orderBy('timestamp', 'desc')
                            ->take(8)
                            ->get()
                            ->sortBy('timestamp');
    
            // Obtener el último mensaje del cliente seleccionado
            $lastMessage = Message::where('customer_id', $this->selectedCustomer)
                            ->orderBy('timestamp', 'desc')
                            ->first();
    
            // Si hay un último mensaje, actualizarlo en la colección de clientes
            if ($lastMessage) {
                // Actualiza el último mensaje solo si el cliente está en la lista
                $customer = $this->customers->firstWhere('id', $this->selectedCustomer);
                if ($customer) {
                    $customer->lastMessage = $lastMessage;
                }
            }
        } else {
            $this->messages = collect(); 
        }
    
        // Actualizar todos los últimos mensajes de los clientes
        $this->updateLastMessages();
    }
    
    private function updateLastMessages()
    {
        // Obtener el último mensaje para cada cliente y actualizar la colección
        $lastMessages = Message::select('customer_id', 'message', 'direction', 'timestamp')
            ->whereIn('customer_id', $this->customers->pluck('id'))
            ->whereIn('timestamp', function ($query) {
                $query->select(DB::raw('MAX(timestamp)'))
                    ->from('messages')
                    ->groupBy('customer_id');
            })
            ->get();
    
        foreach ($this->customers as $customer) {
            $lastMessage = $lastMessages->firstWhere('customer_id', $customer->id);
            if ($lastMessage) {
                $customer->lastMessage = $lastMessage;
            } else {
                // Si no hay mensaje, puedes limpiar el lastMessage o dejarlo como está
                unset($customer->lastMessage);
            }
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
        $this->loadMessages(); // This method should load messages for the selected customer
        $this->loadCustomerTags(); // Load tags associated with the selected customer
    
    }

    public function sendMessage()
{
    $customer = Customer::find($this->selectedCustomer); // Use $this->selectedCustomer

    $whatsapp_api_url = env('WHATSAPP_API_URL');
    $whatsapp_api_version = env('WHATSAPP_API_VERSION');
    $whatsapp_api_number_id = env('WHATSAPP_PHONE_NUMBER_ID');
    $access_token = env('WHATSAPP_ACCESS_TOKEN');

    $whatsapp_full_url = $whatsapp_api_url . $whatsapp_api_version . $whatsapp_api_number_id . '/messages'; 

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
        // Clear the message input
        $this->newMessage = '';

        $this->loadMessages();
    } else {
        // Show error message
        session()->flash('error', 'Error al enviar el mensaje.');
    }

    // Save the message locally
    $message = Message::create([
        'customer_id' => $this->selectedCustomer,
        'message' => $this->newMessage,
        'message_type' => 'text',
        'direction' => 'outbound',
        'status' => 'sent',
        'timestamp' => now(),
    ]);

    // Add the new message to the list
    $this->messages->prepend($message);

    // Clear the new message field
    $this->newMessage = '';
    $this->loadMessages();
}


    //////////////////////////FUNCIONES PARA ASIGNAR LAS ETIQUETAS A LOS CLIENTES///////////////////////////
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
        $customer = Customer::find($this->selectedCustomer);

        // Agregar cada etiqueta seleccionada individualmente, evitando duplicados
        $customer->tags()->syncWithoutDetaching($this->selectedTags);

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


    ///////////////FUNCIONES PARA PODER PRESIONAR LAS ETIQUETAS QUE TIENEN LOS CLIENTES Y PODER ELIMINARLAS/////////////////
    // Mostrar los detalles de la etiqueta sin seleccionar el cliente
    public function showTagDetails($customerId, $tagId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->selectedTag = $customer->tags()->find($tagId);
            $this->selectedCustomerForTag = $customerId;
        
            if ($this->selectedTag) {
                $this->showTagModal = true; // Muestra el modal de detalles de la etiqueta
            } else {
                session()->flash('error', 'Etiqueta no encontrada.');
            }
        } else {
            session()->flash('error', 'Cliente no encontrado.');
        }
    }
    
    // Eliminar la etiqueta específica del cliente
    public function removeCustomerTag()
    {
        if ($this->selectedCustomerForTag && $this->selectedTag) {
            $customer = Customer::find($this->selectedCustomerForTag);
        
            if ($customer) {
                // Desvincular la etiqueta del cliente
                $customer->tags()->detach($this->selectedTag->id);
            
                // Ocultar el modal y mostrar un mensaje de éxito
                $this->showTagModal = false;
                $this->showConfirmModal = false; // Ocultar el modal de confirmación
                session()->flash('success', 'Etiqueta removida con éxito.');
            } else {
                session()->flash('error', 'Cliente no encontrado.');
            }
        }
    }

    ///modal y logica para crear tags
    public function openCreateTagModal()
    {
        $this->createTagModal = true;
    }
    public function closeCreateTagModal()
    {
        $this->createTagModal = false;
    }

    public function createTag()
    {
        //VALIDAR LOS DATOSSSSSSSSSS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $tag = Tag::create([
            'name_tag' => $this->newTag['name_tag'],
            'description' => $this->newTag['description'],
            'color' => $this->newTag['color'],
        ]);
    
        // Obtener al cliente seleccionado
        $customer = Customer::find($this->selectedCustomer);
    
        if ($customer) {
            // Asignar la nueva etiqueta al cliente
            $customer->tags()->attach($tag->id); // Aquí se asocia la etiqueta al cliente
        }
    
        // Reiniciar los campos del formulario
        $this->reset('newTag');
    
        // Cerrar el modal de creación de etiqueta
        $this->createTagModal = false;
    
        // Mostrar mensaje de éxito
        session()->flash('success', 'Etiqueta creada y asignada al cliente con éxito.');
    }
    
    ///modal para abrir info del contacto
    public function openDataModal()
    {
        $this->showDataModal = true;
    }
    public function closeDataModal()
    {
        $this->showDataModal = false;
    }

    public function render()
    {

        return view('livewire.chat-view');
    }
}

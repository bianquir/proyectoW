@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/filament/custom.css') }}">
@endpush

@push('scripts')
 
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Inicialización de Pusher
        var pusher = new Pusher('3d2559e371614fe2127b', { cluster: 'eu' });
    
        pusher.connection.bind('connected', function() {
            console.log('Conectado a Pusher');
        });
    
        var channel = pusher.subscribe('chat-channel');
        
        channel.bind('chat-event', function(data) {
            console.log('Data recibida:', data);  // Log para ver si se recibe
            alert(JSON.stringify(data));
            Livewire.on('refresh', () => {
                // Este evento se dispara cada vez que se recibe un mensaje en tiempo real
                Livewire.emit('updateLastMessages'); // Esto vuelve a cargar los mensajes
            });             
            });
    
        // Revisa si hay errores en la conexión
        pusher.connection.bind('error', function(err) {
            console.error('Error de conexión:', err);
        });
    </script>

       <script src="{{ asset('/js/filament/custom.js') }}"></script>
@endpush


<div section class="chat-wrapper flex flex-col md:flex-row h-screen bg">
    <!-- Sidebar de Contactos (Clientes) -->
    <div class="sidebar-chat w-full md:w-1/4 overflow-y-auto flex-shrink-0">
        <!-- Barra de búsqueda -->
        <div class="search-bar-wrapper p-4 bg-gray-200">
            <input type="text" placeholder="Buscar chat..." class="search-bar input">
        </div>

        <!-- Botones de filtro (Todos, No leídos, Grupos) -->
        <div class="button-wrapper flex justify-around p-2 m-12 bg-white border-b">
            <button class="button-filter">Todos</button>
            <button class="button-filter">No leídos</button>
            <button class="button-filter">Grupos</button>
        </div>

        <!-- Lista de Clientes -->
        <div class="chat-list space-y-1">
            @foreach($customers as $customer)
            @php
                // Verificar si el cliente tiene mensajes no leídos
                 $unreadMessages = $customer->messages()->where('status', 'received')->count();
            @endphp
            <div class="chat-item flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-300 {{ $selectedCustomer === $customer->id ? 'selected' : '' }}  {{ $unreadMessages > 0 ? 'new-message' : '' }}""
                wire:click="selectCustomer({{ $customer->id }})">
               <!-- Avatar del cliente -->
               <div class="avatar mr-4 {{ 'avatar-' . ($customer->id % 5) }}">                
                   <span class="text-white font-bold text-lg">
                       {{ substr($customer->name, 0, 1) }}{{ substr($customer->lastname, 0, 1) }}
                   </span>
               </div>
               <!-- Información del cliente -->
               <div class="flex-1 min-w-0">
                   <!-- Nombre del cliente -->
                   <h3 class="chat-name font-semibold truncate">{{ $customer->name.' '.$customer->lastname }}</h3>
            
                   <!-- Último mensaje y hora -->
                   @if(isset($customer->lastMessage))
                       <div class="last-message flex justify-between text-sm text-gray-600">
                           <span class="message-text truncate">
                               {{ $customer->lastMessage->direction === 'outbound' ? 'Tú: ' : '' }}
                               {{ $customer->lastMessage->message }}
                           </span>
                           <span class="message-time">
                               {{ $this->formatMessageDate($customer->lastMessage->timestamp) }}
                           </span>
                       </div>
                   @endif
               
                   <!-- Etiquetas como botones -->
                   <div class="mt-1 flex flex-wrap">
                       @foreach($customer->tags as $tag)
                           <button style="background-color: {{ $tag->color }};" 
                                   class="tag" 
                                   wire:click="showTagDetails({{ $customer->id }}, {{ $tag->id }})">
                               {{ $tag->name_tag }}
                           </button>
                       @endforeach
                   </div>
               </div>
           </div>
           
            @endforeach
        </div>
    </div>

    <!-- Ventana de Chat -->
    <div class="chat-window flex-1 flex flex-col h-full bg-white overflow-hidden">
        <!-- Header del Chat -->
        <div class="chat-header flex items-center">
            @if ($selectedCustomer)
                <div class="avatar  mr-3 font-bold {{ 'avatar-' . ($selectedCustomer % 5) }}" >
                    <span>{{ substr($customers->find($selectedCustomer)->name, 0, 1) }}</span>
                    <span>{{ substr($customers->find($selectedCustomer)->lastname, 0, 1) }}</span>
                </div>
                <h2 class="text-xl font-bold truncate">
                        {{ $selectedCustomer ? $customers->find($selectedCustomer)->name : 'Selecciona un cliente' }}
                </h2>
                <div class="ml-auto flex gap-x-2">
                    <button class="button-tag" wire:click="openModal">
                        <x-heroicon-s-tag class="h-6 w-6" />
                    </button>
                
                    <button class="button-tag" wire:click="openDataModal">
                        <x-heroicon-s-eye class="h-6 w-6" />
                    </button>
                </div>
            @else
                <!-- Fondo gris o mensaje de guía cuando no hay un cliente seleccionado -->
                <div class="flex-1 text-center text-gray-500">
                
                </div>
            @endif
        </div>
        
        <!-- Mensajes del Chat -->
        <div id="chat-messages" class="chat-messages flex-1 overflow-y-auto p-2" wire:scroll="onScroll">
            @if ($selectedCustomer && $messages->isNotEmpty())
                @php
                    $lastDate = null;
                @endphp
                @foreach($messages as $message)
                @php
                $messageDate = \Carbon\Carbon::parse($message->created_at)->setTimezone('America/Argentina/Buenos_Aires')->format('H:i');
                @endphp

            <!-- Mostrar fecha si el día cambia -->
            @if ($lastDate !== $messageDate)
                <div class="date-separator text-center text-gray-500 my-2">
                    <span>{{ $this->formatMessageDate($message->timestamp) }}</span>
                </div>
                @php
                    $lastDate = $messageDate;
                @endphp
            @endif

            <!-- Mensaje -->
            <div class="message-wrapper w-full flex mb-2">
                <div class="message-container {{ $message->direction == 'outbound' ? 'ml-auto' : 'mr-auto' }} max-w-xs">
                    <div class="message-content p-3 rounded-lg {{ $message->direction == 'outbound' ? 'sent' : 'received' }} shadow-md">
                        <p class="text-xs">{{ $message->message }}</p>
                        <span class="text-time">{{ \Carbon\Carbon::parse($message->timestamp)->format('H:i') }}</span>
                    </div>
                </div>
            </div>
                @endforeach
                @elseif ($selectedCustomer)
                <p class="text-gray-500">No hay mensajes para este cliente.</p>
            @else
                <div class="flex-1 text-center text-gray-500 flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg shadow-lg">
                    <img src="{{ asset('img/CriticalDevs.png') }}" alt="Sin chat seleccionado" class="logo-inicio"> <!-- Ajusta la ruta y tamaño -->
                    <h2 class="text-lg font-semibold">¡Hola!</h2>
                    <p class="mt-4 text-sm">No has seleccionado ningún chat aún. Por favor, elige un cliente para comenzar la conversación.</p>
                </div>
            @endif
        </div>
        
        <!-- Input del Mensaje -->
        @if ($selectedCustomer)
            <div class="chat-input flex items-center p-3 border-t border-gray-300 bg-gray-100">
                <input wire:model="newMessage" type="text" placeholder="Escribe un mensaje..."
                    class="flex-1 p-2 text-sm border rounded-full outline-none focus:ring focus:ring-blue-300">
                    <button wire:click="sendMessage({{ $selectedCustomer }})">
                        Enviar
                    </button>
            </div>
        @endif
    </div>

    <!-- MODALES-->
    <!-- Modal para ver las eituqetas asignadas a cada cliente -->
    @if ($showTagModal && $selectedTag)
        <!-- Modal etiquetas -->
        <div class="modal-overlay">
            <!-- Contenido del modal -->
            <div class="modal-content">
                <h2>Detalles de la Etiqueta</h2>
                <!-- Mostrar detalles de la etiqueta -->
                <div class="flex flex-col mb-4">
                    <p><strong>Nombre:</strong> {{ $selectedTag->name_tag }}</p>
                    <p><strong>Color:</strong> 
                        <span class="tag-color" style="background-color: {{ $selectedTag->color }};">
                            {{ $selectedTag->color }}
                        </span>
                    </p>
                    <p><strong>Descripción:</strong> {{ $selectedTag->description }}</p>
                </div>
                <!-- Botones para eliminar la etiqueta del cliente o cerrar el modal -->
                <div class="flex justify-end space-x-4">
                    <button type="button" class="button-filter" onclick="closeModal()" wire:click="$set('showTagModal', false)">
                        Cerrar
                    </button>
                    <button type="button" class="button-filter" wire:click="removeCustomerTag">
                        Eliminar Etiqueta
                    </button>
                </div>
            </div>
        </div>
    @endif

     <!-- Modal de informacion de contacto -->
     @if ($showDataModal && $selectedCustomer)
        <!-- Modal Ver Datos del Cliente -->
        <div class="modal-overlay">
            <div class="modal-content">
                <h2 class="text-xl font-bold mb-4">Datos del Cliente</h2>
    
                <!-- Mostrar Avatar del Cliente -->
                <div class="flex justify-center mb-4">
                    <div class="avatar {{ 'avatar-' . ($selectedCustomer % 5) }} mr-4">
                        <span class="text-white font-bold text-lg">
                            {{ substr($customers->find($selectedCustomer)->name, 0, 1) }}
                            {{ substr($customers->find($selectedCustomer)->lastname, 0, 1) }}
                        </span>
                    </div>
                </div>
    
                <!-- Mostrar Datos del Cliente -->
                <div class="space-y-2">
                    <p><strong>Nombre:</strong> {{ $customers->find($selectedCustomer)->name . ' ' . $customers->find($selectedCustomer)->lastname }}</p>
                    <p><strong>Email:</strong> {{ $customers->find($selectedCustomer)->email }}</p>
                    <p><strong>Teléfono:</strong> {{ $customers->find($selectedCustomer)->wa_id }}</p>
                    <p><strong>DNI:</strong> {{ $customers->find($selectedCustomer)->dni }}</p>
                    <!-- Agrega más campos según los datos que desees mostrar -->
                </div>
    
                <!-- Botón para cerrar el modal -->
                <div class="flex justify-end mt-4">
                    <button type="button" class="button-filter" wire:click="closeDataModal">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para asignar etiquetas -->
    @if ($showModal)
        <div class="modal-overlay-small" wire:click="closeModal">
            <div class="modal-content-small" wire:click.stop>
                <div class="flex items-center mb-4">
                    <h2 class="text-xl font-bold mr-2">Asignar etiquetas</h2>
                    <button wire:click="openCreateTagModal" class="button-tag">
                        <x-heroicon-m-plus-circle class="h-6 w-6" />
                    </button>
                </div>
            
                <!-- Lista de Etiquetas con Checkboxes, con scroll si son muchas -->
                <div class="flex-1 overflow-y-auto mb-4" style="max-height: 300px;">
                    <ul class="space-y-2">
                        @foreach($tags as $tag)
                            <li class="py-2 px-4 bg-gray-100 rounded flex items-center hover:bg-gray-200 transition duration-200">
                                <!-- Checkbox con margen a la derecha -->
                                <input type="checkbox" value="{{ $tag->id }}" wire:model="selectedTags" class="form-checkbox text-indigo-600 h-5 w-5 rounded-md focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">

                                <!-- Contenedor con margen a la izquierda -->
                                <span class="text-sm font-medium ml-2">{{ $tag->name_tag }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

            
                <!-- Botones para guardar y cerrar, siempre visibles -->
                <div class="flex justify-end space-x-4">
                    <button type="button" class="button-filter" wire:click="closeModal">
                        Cerrar
                    </button>
                    <button type="submit"class="button-filter" wire:click="saveTags">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

    
    <!-- modal para crear etiquetas-->
    @if ($createTagModal)
        <div class="modal-overlay-small" wire:click="closeCreateTagModal">
            <div class="modal-content-small" wire:click.stop>
                <h2 class="text-xl font-bold mb-4">Crear nueva etiqueta</h2>
                
                <!-- Formulario para crear una nueva etiqueta -->
                <div>
                    <form wire:submit.prevent="createTag">
                        <div class="mb-4">
                            <label for="name_tag" class="block text-sm font-medium text-gray-700">Nombre de Tag</label>
                            <input type="text" wire:model.defer="newTag.name_tag" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <input type="text" wire:model.defer="newTag.description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="mb-4">
                            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                            <input type="color" wire:model.defer="newTag.color" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>
                        <div class="flex justify-end">
                            <button type="button" class="button-filter" wire:click="closeCreateTagModal">
                                Cancelar
                            </button>
                            <button type="submit" class="button-filter ml-2">
                                Crear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
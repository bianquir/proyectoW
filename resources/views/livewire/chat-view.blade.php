@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/filament/custom.css') }}">
@endpush

@push('scripts')
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
        <div class="chat-item flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-300 {{ $selectedCustomer === $customer->id ? 'selected' : '' }}"
        wire:click="selectCustomer({{ $customer->id }})">
            <!-- Avatar del cliente -->
            <div class="avatar  mr-4 {{ 'avatar-' . ($customer->id % 5) }}">                
                <span class="text-white font-bold text-lg">
                    {{ substr($customer->name, 0, 1) }}{{ substr($customer->lastname, 0, 1) }}
                </span>
            </div>
            <!-- Nombre del cliente -->
            <div class="flex-1 min-w-0">
                <h3 class="chat-name font-semibold truncate">{{ $customer->name.' '.$customer->lastname }}</h3>
                <!-- Mostrar etiquetas como botones -->
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
            <div class="ml-auto mr-4">
                <button class="button-tag" wire:click="openModal">
                    <x-heroicon-s-tag class="h-6 w-6" />
                </button>
            </div>
            @else
            <!-- Fondo gris o mensaje de guía cuando no hay un cliente seleccionado -->
            <div class="flex-1 text-center text-gray-500">
               
            </div>
            @endif
            <!-- Modal -->
            @if ($showModal)
            <div class="modal-overlay-small" wire:click="closeModal">
                <div class="modal-content-small">
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
                                <li class="py-2 px-4 bg-gray-100 rounded flex items-center">
                                    <input type="checkbox" value="{{ $tag->id }}" wire:model="selectedTags" class="mr-2">
                                    <span>{{ $tag->name_tag }}</span>
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
        </div>

        @if($loadingMore)
            <div class="text-center p-2 mb-2">
                <span class="loader">Cargando más mensajes...</span>
            </div>
        @endif

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

        <!-- Mensajes del Chat -->
        <div id="chat-messages" class="chat-messages flex-1 overflow-y-auto p-2" wire:scroll.debounce.250ms="onScroll">
            @if ($selectedCustomer && $messages->isNotEmpty())
                @php
                    $lastDate = null;
                @endphp
                @foreach($messages as $message)
                @php
                $messageDate = \Carbon\Carbon::parse($message->timestamp)->format('Y-m-d');
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
                    <div class="message flex mb-2 {{ $message->direction == 'outbound' ? 'justify-end' : 'justify-start' }}">
                        <div class="message-content p-3 rounded-lg max-w-full md:max-w-xs {{ $message->direction == 'outbound' ? 'sent' : 'received' }} shadow-md">
                            <p class="text-xs">{{ $message->message }}</p>
                            <span class="text-time">{{ \Carbon\Carbon::parse($message->timestamp)->format('H:i') }}</span>
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
</div>
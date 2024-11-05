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
            <div class="avatar flex items-center justify-center mr-4 overflow-hidden">
                <img src="{{ asset('img/CriticalDevs.png') }}" alt="avatar" class="w-full h-full rounded-full">
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
    <!-- Fondo borroso -->
    <div class="blur-background"></div>
    
    <!-- Modal con animación de aparición suave -->
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-content show bg-white w-11/12 md:w-1/3 h-auto max-h-3/4 rounded-lg shadow-lg p-6 flex flex-col">
            <h2 class="text-xl font-bold mb-4">Detalles de la Etiqueta</h2>

            <!-- Mostrar detalles de la etiqueta -->
            <div class="flex flex-col mb-4">
                <p><strong>Nombre:</strong> {{ $selectedTag->name_tag }}</p>
                <p><strong>Color:</strong> 
                    <span style="background-color: {{ $selectedTag->color }}; padding: 4px 8px; border-radius: 4px;">
                        {{ $selectedTag->color }}
                    </span>
                </p>
                <p><strong>Descripción:</strong> {{ $selectedTag->description }}</p>
            </div>

            <!-- Botones para eliminar la etiqueta del cliente o cerrar el modal -->
            <div class="flex justify-end space-x-4">
                <button type="button" class="button-filter" wire:click="$set('showTagModal', false)">
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
            <div class="avatar flex items-center justify-center mr-3 overflow-hidden">
                <span>{{ substr($customers->find($selectedCustomer)->name, 0, 1) }}</span>
                <span>{{ substr($customers->find($selectedCustomer)->lastname, 0, 1) }}</span>
            </div>
            <h2 class="text-xl font-bold truncate">
                    {{ $selectedCustomer ? $customers->find($selectedCustomer)->name : 'Selecciona un cliente' }}
            </h2>
            <div class="ml-auto mr-4"> <!-- Añadido mr-4 para más espacio a la derecha -->
                <button class="button-filter" wire:click="openModal">
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
            <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white w-11/12 md:w-1/3 h-auto max-h-3/4 rounded-lg shadow-lg p-6 flex flex-col">
                    <h2 class="text-xl font-bold mb-4">Asignar Etiquetas al Cliente</h2>
                
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
                            Guardar Etiquetas
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
                <p class="text-gray-500">Selecciona un cliente para ver los mensajes.</p>
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
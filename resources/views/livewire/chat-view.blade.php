@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/filament/custom.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('/js/filament/custom.js') }}"></script>
@endpush

<div class="flex chat-wrapper h-screen overflow-hidden bg">
    <!-- Sidebar de Contactos (Clientes) -->
<div class="sidebar-chat w-1/4 overflow-y-auto flex-shrink-0">
    <!-- Barra de búsqueda -->
    <div class="search-bar-wrapper p-4 bg-gray-200">
        <input type="text" placeholder="Buscar o empezar un chat nuevo" class="search-bar input">
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
                    <img src="{{ $customer->avatar_url ?? asset('default-avatar.png') }}" alt="avatar" class="w-full h-full rounded-full">
                </div>
                <!-- Nombre del cliente y último mensaje -->
                <div class="flex-1 min-w-0">
                    <h3 class="chat-name font-semibold truncate">{{ $customer->name }}</h3>
                    <p class="last-message text-sm text-gray-600 truncate">{{ $customer->last_message }}</p>
                </div>
                <!-- Tiempo del último mensaje -->
                <span class="text-xs text-gray-500 whitespace-nowrap">{{ $customer->last_message_time }}</span>
            </div>
        @endforeach
    </div>
</div>


    <!-- Ventana de Chat -->
    <div class="chat-window flex-1 flex flex-col">
        <!-- Header del Chat -->
        <div class="chat-header flex items-center">
            <div class="avatar flex items-center justify-center mr-3 overflow-hidden">
                <span>{{ substr($customer->name, 0, 1) }}</span>
            </div>
            <h2 class="text-xl font-bold truncate">
                {{ $selectedCustomer ? $customers->find($selectedCustomer)->name : 'Selecciona un cliente' }}
            </h2>
        </div>

        @if($loadingMore)
            <div class="text-center p-2 mb-2">
                <span class="loader">Cargando más mensajes...</span>
            </div>
        @endif


        <!-- Mensajes del Chat -->
        <div id="chat-messages" class="chat-messages flex-1 overflow-y-auto p-4" wire:scroll.debounce.250ms="onScroll">
            @if ($messages->isNotEmpty())
                @foreach($messages as $message)
                    <div class="message flex mb-2 {{ $message->direction == 'outbound' ? 'justify-end' : 'justify-start' }}">
                        <div class="message-content p-3 rounded-lg max-w-xs {{ $message->direction == 'outbound' ? 'sent' : 'received' }} shadow-md">
                            <p class="text-xs">{{ $message->message }}</p>
                            <span class="text-xs block mt-1">{{ \Carbon\Carbon::parse($message->timestamp)->format('H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">No hay mensajes para este cliente.</p>
            @endif
        
            <!-- Indicar que se están cargando más mensajes -->
            @if($loadingMore)
                <div class="text-center p-2">Cargando más mensajes...</div>
            @endif
        </div>
        
        <!-- Input del Mensaje -->
        @if ($selectedCustomer)
            <div class="chat-input flex items-center">
                <input wire:model="newMessage" type="text" placeholder="Escribe un mensaje...">
                <button wire:click="sendMessage">Enviar</button>
            </div>
        @endif
    </div>
</div>
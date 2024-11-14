<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Mail\OrderCreatedMail;
use App\Models\Order;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string
    {
        return 'Crear un nuevo pedido';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Obtiene el pedido recién creado
        $order = $this->record;
    
        if ($order->customer->email) {
            // Si el cliente tiene correo, se envía el correo
            Mail::to($order->customer->email)->send(new OrderCreatedMail($order));
        } else {
            Notification::make()
            ->title('Error')
            ->danger()  
            ->body('El cliente no tiene un correo electrónico registrado. No se pudo enviar el correo.')
            ->send();
        }
    }
    
}

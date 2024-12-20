<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear pedido'),
        ];
    }

    public function getTitle(): string
    {
        return 'Listado de pedidos';
    }
    public function getBreadcrumbs(): array
    {
        return [
            url('/admin')=> 'Inicio',
            OrderResource::getUrl()=>'Pedidos',
            'Listado de pedidos',
        ];
    }
}

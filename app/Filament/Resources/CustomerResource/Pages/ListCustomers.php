<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Listado de clientes';
    }

    public function getBreadcrumbs(): array
    {
        return [
            url('/admin')=> 'Inicio',
            CustomerResource::getUrl()=>'Clientes',
            'Listado de clientes',
        ];
    }
}

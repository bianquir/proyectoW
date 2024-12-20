<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear producto'),
        ];
    }

    public function getTitle(): string
    {
        return 'Listado de Productos';
    }

    public function getBreadcrumbs(): array
    {
        return [
            url('/admin')=> 'Inicio',
            ProductResource::getUrl()=>'Productos',
            'Listado de productos',
        ];
    }
}

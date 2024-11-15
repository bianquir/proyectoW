<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function getTitle(): string
    {
        return 'Crear nuevo Producto';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
    public function getBreadcrumbs(): array
    {
        return [
            url('/admin')=> 'Inicio',
            ProductResource::getUrl()=>'Productos',
            'Crear producto',
        ];
    }
}

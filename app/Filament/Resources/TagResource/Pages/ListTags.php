<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear etiqueta'),
        ];
    }

    public function getTitle(): string
    {
        return 'Listado de Etiquetas';
    }

    public function getBreadcrumbs(): array
    {
        return [
            url('/admin')=> 'Inicio',
            TagResource::getUrl()=>'Etiquetas',
            'Listado de etiquetas',
        ];
    }
}

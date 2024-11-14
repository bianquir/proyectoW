<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Datos personales')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('dni')
                    ->label('DNI')
                    ->required()
                    ->numeric()
                    ->maxLength(8)
                    ->rule('digits:8')
                    ->rule('unique:customers,dni')  // Validación de unicidad para el campo DNI
                    ->placeholder('Ejemplo: 12345678'),

                    Forms\Components\TextInput::make('cuil')
                    ->label('CUIL')
                    ->required()
                    ->numeric()
                    ->maxLength(13)
                    ->rule('unique:customers,cuil')  // Validación de unicidad para el campo CUIL
                    ->placeholder('Ejemplo: 20-12345678-3'),

                    
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(30)
                        ->rule('regex:/^[\pL\s]+$/u')
                        ->placeholder('Ejemplo: Juan'),
                    
                    Forms\Components\TextInput::make('lastname')
                        ->label('Apellido')
                        ->maxLength(30)
                        ->rule('regex:/^[\pL\s]+$/u') 
                        ->placeholder('Ejemplo: Pérez'),
                    
                    Forms\Components\TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email() 
                        ->required()
                        ->maxLength(255),
                    
                    Forms\Components\TextInput::make('address')
                        ->label('Dirección')
                        ->maxLength(30),
                ]),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') 
            ->columns([
                Tables\Columns\TextColumn::make('dni')
                    ->label('DNI')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cuil')
                    ->label('CUIL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Apellido')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wa_id')
                    ->label('ID Whatsapp')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->label('Mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Dirección')
                    ->searchable(),
                // Tables\Columns\IconColumn::make('whatsapp_opt_in')
                //     ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última actualización') 
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->deselectAllRecordsWhenFiltered(false)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}

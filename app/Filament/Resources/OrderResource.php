<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput as FormsTextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationLabel = 'Pedidos';
    protected static ?string $navigationIcon = 'heroicon-c-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos del Pedido')
                    ->columns(2)
                    ->schema([
                        Select::make('customer_id')
                            ->label('Cliente')
                            ->options(Customer::all()->pluck('name', 'id')) 
                            ->required()
                            ->searchable() // Permite buscar clientes por nombre
                            ->placeholder('Seleccionar cliente')
                            // Añadir evento `afterStateUpdated` para verificar el correo del cliente
                            ->afterStateUpdated(function ($state, $set) {
                                // Verifica si el cliente seleccionado tiene correo
                                $customer = Customer::find($state);
                                if ($customer && !$customer->email) {
                                    // Si no tiene correo, muestra una notificación de alerta
                                    Notification::make()
                                        ->title('Advertencia')
                                        ->danger()  // Tipo de alerta (advertencia o error)
                                        ->body('Este cliente no tiene un correo electrónico registrado.')
                                        ->send();
                                }
                            }),
                        DatePicker::make('shipping_day')
                            ->label('Día del pedido')
                            ->required(),
                        
                        TextInput::make('state')
                            ->label('Estado')
                            ->required()
                            ->maxLength(50),
                    ]),
                
                Section::make('Detalles del Pedido')
                    ->schema([
                        Repeater::make('order_details')
                            ->label('Productos')
                            ->relationship('orderDetails')
                            ->required()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Producto')
                                    ->options(Product::all()->pluck('name', 'id'))
                                    ->required(),
                        
                                FormsTextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->required()
                                    ->rules(['min:1', 'integer']), // Usamos reglas de validación de Laravel
                            ])
                            ->columns(2)
                            ->createItemButtonLabel('Agregar producto'),
                    ])
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') 
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->sortable(),
                
                    TextColumn::make('shipping_day')
                    ->label('Día del pedido')
                    ->getStateUsing(fn ($record) => Carbon::parse($record->shipping_day)->format('d/m/Y'))
                    ->sortable(),

                TextColumn::make('state')
                    ->label('Estado')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Última actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

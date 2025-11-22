<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $relatedResource = OrderResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_name')->label('Producto'),
                TextColumn::make('quantity')->label('Cantidad'),
                TextColumn::make('price')->label('Precio'),
                TextColumn::make('subtotal')->label('Subtotal'),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

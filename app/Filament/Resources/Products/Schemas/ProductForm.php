<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Gs'),
                TextInput::make('sale_price')
                    ->numeric()
                    ->prefix('Gs'),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('images')
                    ->image()
                    ->multiple()
                    ->disk('public')
                    ->directory('products')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->maxFiles(5)
                    ->reorderable()
                    ->downloadable()
                    ->previewable(true)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
            ]);
    }
}

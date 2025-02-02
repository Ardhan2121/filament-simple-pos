<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->placeholder('Enter the product name')
                    ->autofocus(),
                Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Enter the product description'),
                TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->placeholder('Enter the product price')
                    ->integer(),
                TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->placeholder('Enter the product stock')
                    ->integer(),
                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->placeholder('Upload the product image'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                ImageColumn::make('image')
                    ->label('Image')
                    ->size(50)
                    ->default('https://placehold.co/400')
                    ->circular()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->description(fn (Product $product) => $product->description)
                    ->wrap()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(function (Product $product) {
                        if ($product->stock > 10) {
                            return 'success';
                        } elseif ($product->stock > 0) {
                            return 'warning';
                        }
                        return 'danger';
                    })
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

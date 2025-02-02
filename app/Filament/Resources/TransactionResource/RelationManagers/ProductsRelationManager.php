<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Product'),
                TextColumn::make('pivot.qty')
                    ->label('Qty')
                    ->summarize(Sum::make()->label('Total')),
                TextColumn::make('pivot.subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->summarize(Sum::make()->label('Total')->money('IDR')),
            ])
            ->filters([
                //
            ])
            ->actions([])
            ->bulkActions([]);
    }
}

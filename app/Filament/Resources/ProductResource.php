<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

   public static function getNavigationLabel(): string
    {
        return __('Manage Products');
    }

    protected static ?string $navigationGroup = 'Products Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('Product Name'))
                            ->required(),
                        Forms\Components\TextInput::make('code')
                            ->label(__('Product Code'))
                            ->helperText(__('A unique code for the product, e.g., SKU or UPC.'))
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('unit_id')
                        ->label(__('Unit'))
                            ->relationship('unit', 'key')
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->label(__('Price'))
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label(__('Quantity'))
                            ->numeric()
                            ->required(),
                         Forms\Components\TextInput::make('safety_stock')
                            ->label(__('Safety Stock'))
                            ->helperText( __('The minimum stock level to maintain for this product.'))
                            ->numeric(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->helperText(__('A detailed description of the product.')),
                        Forms\Components\KeyValue::make('data')
                            ->label(__('Extra Details'))
                            ->helperText(__('Additional information about the product.')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('Product Code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Product Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('NGN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('safety_stock')
                    ->label(__('Safety Stock')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->datetime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

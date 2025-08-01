<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Product;
use App\Models\Purchase;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function getNavigationLabel(): string
    {
        return __('Purchases');
    }

    protected static ?string $navigationGroup = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(3)
                    ->heading(__('Provider Details'))
                    ->schema([
                        Forms\Components\Select::make('provider_id')
                            ->label(__('Provider'))
                            ->required()
                            ->relationship('provider', 'name')
                            ->createOptionForm(function(){
                                $tenantField = [
                                    Forms\Components\Hidden::make('tenant_id')
                                        ->default(Filament::getTenant()->id),
                                ];
                                return array_merge(CustomerResource::getCustomerFormSchema(), $tenantField);
                            })
                            ->searchable(),
                        Forms\Components\TextInput::make('invoice_no')
                            ->label(__('Invoice No'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('purchase_date')
                            ->label(__('Purchase Date'))
                            ->required(),

                    ]),
                Forms\Components\Section::make()
                    ->heading(__('Product Details'))
                    ->schema([
                        Forms\Components\Repeater::make('products')
                            ->label(__('Products'))
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->options(Product::pluck('name', 'id')->toArray())
                                    ->label(__('Product'))
                                    ->required()
                                    ->searchable()
                                    ->createOptionForm(function () {
                                        //              Forms\Components\TextInput::make('name')
                                        //     ->label(__('Product Name'))
                                        //     ->required(),
                                        // Forms\Components\TextInput::make('code')
                                        //     ->label(__('Product Code'))
                                        //     ->helperText(__('A unique code for the product, e.g., SKU or UPC.'))
                                        //     ->required()
                                        //     ->unique(ignoreRecord: true)
                                    }),
                                Forms\Components\TextInput::make('quantity')
                                    ->label(__('Quantity'))
                                    ->required()
                                    ->numeric()
                                    ->reactive()
                                    ->default(0)
                                    ->afterStateUpdated(function (callable $get, Set $set) {
                                        $price = (float) $get('price');
                                        $quantity = (int) $get('quantity');
                                        $total = $price * $quantity;
                                        $set('total', $total);
                                    }),

                                Forms\Components\TextInput::make('price')
                                    ->label(__('Price'))
                                    ->required()
                                    ->numeric()
                                    ->reactive()
                                    ->default(0)
                                    ->afterStateUpdated(function (callable $get, Set $set) {
                                        $price = (float) $get('price');
                                        $quantity = (int) $get('quantity');
                                        $total = $price * $quantity;
                                        $set('total', $total);
                                    }),

                                Forms\Components\TextInput::make('total')
                                    ->label(__('Total'))
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->disabled(),
                            ])
                            ->columns(4)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}

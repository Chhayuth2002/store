<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Sales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema(static::getFormSchema(''))
                            ->columns(2),

                        Section::make()
                            ->schema(static::getFormSchema('orderItems')),
                    ])
                    ->columnSpan(['lg' => fn (?Order $record) => $record === null ? 3 : 2]),
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('total_price')
                                    ->disabled()
                                    ->dehydrated()
                                    ->placeholder(function ($get, Set $set) {
                                        $sum = 0;
                                        foreach ($get('orderItems') as $sku) {
                                            $sum = $sum + ($sku['price'] * $sku['quantity']);
                                        }
                                        $set('total_price', $sum);
                                        return $sum;
                                    }),
                            ]),
                        Section::make()
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label('Created at')
                                    ->content(fn (Order $record): ?string => $record->created_at?->diffForHumans()),

                                Placeholder::make('updated_at')
                                    ->label('Last modified at')
                                    ->content(fn (Order $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])->hidden(fn (?Order $record) => $record === null),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->sortable(),
                TextColumn::make('customer.first_name')
                    ->sortable()
                    ->label('Customer Name')
                    ->formatStateUsing(function ($state, Order $order) {
                        return $order->customer->first_name . ' ' . $order->customer->last_name;
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                TextColumn::make('total_price')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money(),
                    ]),
                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::where('status', 'pending')->count();
    }

    public static function getFormSchema(string $section = null): array
    {
        if ($section === 'orderItems') {
            return [
                Repeater::make('orderItems')
                    ->relationship()
                    ->schema([
                        Select::make('product_variant_id')
                            ->label('Product')
                            // ->options(ProductVariant::query()->pluck('sku', 'id'))
                            ->required()
                            ->options(fn () => ProductVariant::with('product')->get()->groupBy('product.name')->map->pluck('name', 'id'))
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('price', ProductVariant::find($state)?->price ?? 0))
                            ->columnSpan([
                                'md' => 5,
                            ])
                            ->preload()
                            ->searchable(),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->default(1)
                            ->columnSpan([
                                'md' => 2,
                            ])
                            ->reactive()
                            ->required(),

                        TextInput::make('price')
                            ->label('Unit Price')
                            ->disabled()
                            ->numeric()
                            ->dehydrated()
                            ->required()
                            ->columnSpan([
                                'md' => 3,
                            ]),
                    ])
                    ->defaultItems(1)
                    ->columns([
                        'md' => 10,
                    ])
                    ->required(),
            ];
        }
        return [
            Grid::make()
                ->schema([
                    TextInput::make('number')
                        ->default('OR-' . random_int(100000, 999999))
                        ->disabled()
                        // ->required()
                        ->dehydrated(),

                    Select::make('customer_id')
                        ->relationship(
                            name: 'customer',
                            modifyQueryUsing: fn (Builder $query) => $query->orderBy('first_name')->orderBy('last_name'),
                        )
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
                        ->searchable(['first_name', 'last_name'])
                        // ->required()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('first_name')
                                // ->required()
                                ->maxLength(100),
                            TextInput::make('last_name')
                                // ->required()
                                ->maxLength(100),
                            TextInput::make('email')
                                ->email()
                                // ->required()
                                ->maxLength(100),
                            TextInput::make('password')
                                ->password()
                                // ->required()
                                ->maxLength(100),
                        ])
                        ->createOptionAction(function (Action $action) {
                            return $action
                                ->modalHeading('Create customer')
                                ->modalButton('Create customer')
                                ->modalWidth('lg');
                        }),

                    Select::make('status')
                        ->options(OrderStatus::class)
                        // ->required()
                        ->native(false),
                ]),
            Grid::make()
                ->schema([
                    TextInput::make('country')
                        // ->required()
                        ->maxLength(100),
                    TextInput::make('city')
                        // ->required()
                        ->maxLength(100),
                    TextInput::make('state')
                        ->label('State / Province')
                        // ->required()
                        ->maxLength(100),
                    TextInput::make('zip')
                        ->label('Zip / Postal Code')
                        // ->required()
                        ->maxLength(100),

                ])->columns(2),
            TextInput::make('street')
                ->label('Street Address')
                // ->required()
                ->maxLength(100)
                ->columnSpanFull(),
            RichEditor::make('note')
                ->columnSpanFull()
        ];
    }
    public static function getWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }
    // public static function printInvoice(Order $order)
    // {
    //     $orderDate    = $order->created_at->format('jFY');
    //     $workerName     = str($order->first_name)->replace(' ', '')->headline();
    //     $fileName       = "order_invoice_{$orderDate}_{$workerName}.pdf";
    //     $total          = 0;
    //     $pdf            = Pdf::loadView('print', compact('order', 'fileName', 'total'));

    //     return response()->streamDownload(fn () => print($pdf->output()), $fileName);
    // }
}

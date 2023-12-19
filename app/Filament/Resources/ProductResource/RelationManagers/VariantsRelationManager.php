<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';
    protected static ?string $recordTitleAttribute = 'sku';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('sku')
                                    ->required()
                                    ->maxLength(20),
                                TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->maxLength(50),
                                TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated(),
                            ]),
                        TextInput::make('price')
                            ->required()
                            ->numeric(),
                        TextInput::make('inventory_quantity')
                            ->numeric()
                            ->label('inventory'),
                        Repeater::make('productAttributes')
                            ->relationship()
                            ->cloneable()
                            ->collapsible()
                            ->schema([
                                Select::make('attribute_id')
                                    ->label('Attribute')
                                    ->options(Attribute::all()->pluck('name', 'id')->toArray())
                                    ->reactive(),
                                Select::make('attribute_option_id')
                                    ->label('Attribute value')
                                    ->options(function (callable $get) {
                                        $attribute = Attribute::find($get('attribute_id'));
                                        if (!$attribute) {
                                            return AttributeOption::all()->pluck('value', 'id');
                                        }
                                        return $attribute->options->pluck('value', 'id');
                                    })->live(onBlur: true),
                            ])->defaultItems(1)->columnSpanFull()
                            ->columns(2),

                    ])->columnSpan(['lg' => 2])
                    ->columns(2),
                Section::make()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('Image')
                            ->multiple()
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(['lg' => 1]),
                Section::make()
                    ->schema([

                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (ProductVariant $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (ProductVariant $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columns(2)
                    ->columnSpan(['lg' => 2])
                    ->hidden(fn (?ProductVariant $record) => $record === null),

            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sku')
            ->columns([
                Tables\Columns\TextColumn::make('sku'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('attributeOptions.value'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('inventory_quantity')
                    ->label('Quantity'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ReplicateAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

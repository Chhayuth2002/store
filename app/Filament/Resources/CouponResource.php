<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $recordTitleAttribute = 'code';
    protected static ?string $navigationGroup = 'Catalogs';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Rule Information')
                    ->schema([
                        TextInput::make('code')
                            ->required(),
                        Select::make('type')
                            ->options([
                                'percentage' => 'Percentage',
                                'fixed' => 'Fixed',
                            ]),
                        TextInput::make('amount')
                            ->numeric(),
                        Toggle::make('status')
                            ->inline(false),
                        TextInput::make('usage_limit')
                            ->numeric(),
                        TextInput::make('usage_count')
                            ->numeric()
                            ->default(0),
                        DatePicker::make('start_date')
                            ->label('From'),
                        DatePicker::make('end_date')
                            ->label('To')
                    ])->columns(2)
                    ->collapsible()
                    ->columnSpan(['lg' =>  2]),

                Forms\Components\Grid::make()
                    ->schema([
                        Section::make('Condition')
                            ->schema([
                                Select::make('products')
                                    ->multiple()
                                    ->preload(),
                                SelectTree::make('categories')
                                    ->relationship('categories', 'name', 'parent_id', function ($query) {
                                        return $query;
                                    })->enableBranchNode()
                                    ->placeholder(__('Please select a category'))
                                    ->withCount()
                                    ->searchable()
                            ])->collapsible(),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->content(fn (Coupon $record): ?string => $record->created_at?->diffForHumans()),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->content(fn (Coupon $record): ?string => $record->updated_at?->diffForHumans()),
                            ])->hidden(fn (?Coupon $record) => $record === null)
                    ])->columnSpan(['lg' => 1])

            ])->columns(3);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code'),
                TextColumn::make('usage_count'),
                TextColumn::make('start_date')
                    ->date(),
                TextColumn::make('end_date')
                    ->date(),
                ToggleColumn::make('status'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}

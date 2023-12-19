<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\VariantsRelationManager;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Product;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Catalogs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Product Information')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->reactive(),
                                RichEditor::make('description'),
                                SpatieMediaLibraryFileUpload::make('thumbnail')
                                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png']),
                            ])
                    ])->columnSpan(['lg' => 2]),
                Group::make()
                    ->schema([
                        Section::make('Status')
                            ->schema([
                                Toggle::make('status')
                                    ->label('Visible')
                                    ->default(false),
                                Toggle::make('is_new')
                                    ->label('New')
                                    ->default(false),
                                Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->default(false),
                                DatePicker::make('available_date'),



                            ]),
                        Section::make('Associations')
                            ->schema([
                                Select::make('brand_id')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->preload(),
                                SelectTree::make('categories')
                                    ->relationship('categories', 'name', 'parent_id', function ($query) {
                                        return $query;
                                    })
                                    // ->grouped(false)
                                    ->enableBranchNode()
                                    ->withCount()

                            ]),
                        Section::make()
                            ->schema([

                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Created at')
                                    ->content(fn (Product $record): ?string => $record->created_at?->diffForHumans()),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Last modified at')
                                    ->content(fn (Product $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->columnSpan(['lg' => 2])
                            ->hidden(fn (?Product $record) => $record === null),
                    ]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label('Image'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Product Name'),
                TextColumn::make('variants_count')
                    ->counts('variants')
                    ->default(0)
                    ->sortable(),
                TextColumn::make('variants_sum_inventory_quantity')
                    ->label('Inventory')
                    ->sortable()
                    ->default(0)
                    ->sum('variants', 'inventory_quantity'),
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
            VariantsRelationManager::class,
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

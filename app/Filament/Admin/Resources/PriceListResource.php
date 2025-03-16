<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\ItemCat;
use App\Models\Project;
use Filament\Forms\Form;
use App\Models\PriceList;
use Filament\Tables\Table;
use Filament\Resources\Resource;
// use Filament\Forms\Components\Select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\PriceListResource\Pages;
use App\Filament\Admin\Resources\PriceListResource\RelationManagers;

class PriceListResource extends Resource
{
    protected static ?string $model = PriceList::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string  $pluralModelLabel  = 'تسعير المنافسات';
    protected static ?string $modelLabel =' تسعير منافسة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('project_id')
                    ->label('المنافسة')
                    ->options(Project::pluck('name', 'id'))
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(fn ($set) => $set('item_id', null))
                    ->searchable()
                    ->preload(),
                Select::make('item_id')
                    ->label('البند من جدول الكميات')
                    ->options(fn (callable $get) => $get('project_id') ? Item::where('project_id', $get('project_id'))->pluck('description', 'id') : [])
                    ->required()
                    ->reactive()
                    ->searchable()
                    ->preload(),
                    Repeater::make('priceListItems')
                    ->label('مكونات البند وتصنيفه وتسعيره')
                    ->relationship()
                    ->schema([
                        Select::make('item_cat_id')
                            ->label('تصنيف العنصر')
                            ->searchable()
                            ->options(fn () => ItemCat::pluck('name', 'id'))
                            ->required(),

                        Select::make('measurement_unit_id') 
                            ->label('وحدة القياس والتوريد')
                            ->relationship('measurementUnit', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                            Select::make('material_id') 
                            ->label('المادة الخام ')
                            ->relationship('material', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('المادة الخام ')
                                    ->required()
                            ])
                            ->required(),
                        Textarea::make('description')
                            ->label('وصف العنصر')
                            ->required(),
                        TextInput::make('quantity')
                            ->label('الكمية المطلوبة بالبند')
                            ->numeric()
                            ->required(),
                        TextInput::make('price')
                            ->label('سعر الكمية')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->addActionLabel('اضافة عنصر اخر')
                    ->collapsible()
                    ->columnSpanFull()
                
            ]);
    }
   
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('project.name')
                ->label('المشروع')
                ->sortable()
                ->searchable(),

            TextColumn::make('item.description')
                ->label('البند')
                ->sortable()
                ->limit(90) 
                ->tooltip(fn ($record) => $record->item->description)
                ->searchable(),
            TextColumn::make('priceListItems.itemCat.name')
                ->label('تصنيف البند')
                ->listWithLineBreaks()
                ->searchable(),

            TextColumn::make('priceListItems.description')
                ->label('وصف البند')
                ->limit(90) 
                ->searchable(),

            TextColumn::make('priceListItems.quantity')
                ->label('   الكمية المطلوبة بالبند')
                ->listWithLineBreaks(),
                TextColumn::make('priceListItems.measurementUnit.name')
                ->label('وحدة القياس والتوريد')
                ->listWithLineBreaks()
                ->searchable(),
            TextColumn::make('priceListItems.price')
                ->label('   سعر الكمية')
                ->listWithLineBreaks()
                ->money('SAR'),
        ])
        ->filters([
            \Filament\Tables\Filters\Filter::make('project_and_item')
                ->form([
                    Select::make('project_id')
                        ->label('Project')
                        ->options(Project::pluck('name', 'id'))
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('item_id', null)),
                    Select::make('item_id')
                        ->label('Item')
                        ->options(fn (callable $get) => $get('project_id')
                            ? Item::where('project_id', $get('project_id'))->pluck('description', 'id')
                            : []),
                ])
                ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                    if (! empty($data['project_id'])) {
                        $query->where('project_id', $data['project_id']);
                    }
                    if (! empty($data['item_id'])) {
                        $query->where('item_id', $data['item_id']);
                    }
                    return $query;
                }),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPriceLists::route('/'),
            'create' => Pages\CreatePriceList::route('/create'),
            'edit' => Pages\EditPriceList::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\ItemCat;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\ItemCatResource\Pages;
use App\Filament\SuperAdmin\Resources\ItemCatResource\RelationManagers;

class ItemCatResource extends Resource
{
    protected static ?string $model = ItemCat::class;
    protected static ?string $modelLabel = '  تصنيف البنود';
    protected static ?string $pluralModelLabel = '  تصنيفات البنود';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label(' اسم التصنيف')
                ->required()
                ->unique(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()
                ->label(' اسم التصنيف'),
                TextColumn::make('created_at')->dateTime()
                ->label(' تاريخ الانشاء'),
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
            'index' => Pages\ListItemCats::route('/'),
            'create' => Pages\CreateItemCat::route('/create'),
            'edit' => Pages\EditItemCat::route('/{record}/edit'),
        ];
    }
}
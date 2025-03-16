<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\MeasurementUnitResource\Pages;
use App\Filament\SuperAdmin\Resources\MeasurementUnitResource\RelationManagers;
use App\Models\MeasurementUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeasurementUnitResource extends Resource
{
    protected static ?string $model = MeasurementUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel ='  وحدات القياس';
    protected static ?string $pluralModelLabel  = ' وحدات القياس   ';


    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('اسم الوحدة')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('symbol')
                ->label('الرمز')
                ->required()
                ->maxLength(50),

            Forms\Components\Textarea::make('description')
                ->label('الوصف')
                ->maxLength(65535)
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('اشم الوحدة')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('symbol')
                ->label('الرمز')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('description')
                ->label('الوصف')
                ->limit(50)
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('تاريخ الانشاء')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListMeasurementUnits::route('/'),
            'create' => Pages\CreateMeasurementUnit::route('/create'),
            'edit' => Pages\EditMeasurementUnit::route('/{record}/edit'),
        ];
    }
}
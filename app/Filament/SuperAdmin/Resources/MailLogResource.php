<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\MailLog;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\MailLogResource\Pages;
use App\Filament\SuperAdmin\Resources\MailLogResource\RelationManagers;

class MailLogResource extends Resource
{
    protected static ?string $model = MailLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'الإعدادات';
    // protected static ?string $navigationIcon = 'heroicon-o-mail';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('subject')->sortable()->searchable(),
            TextColumn::make('recipient')->sortable()->searchable(),
            TextColumn::make('sender')->sortable(),
            TextColumn::make('status')
                ->sortable()
                ->badge()
                ->color(fn ($record) => $record->status === 'sent' ? 'success' : 'danger'),
            TextColumn::make('created_at')->label('تاريخ الإرسال')->sortable(),
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
            'index' => Pages\ListMailLogs::route('/'),
            'create' => Pages\CreateMailLog::route('/create'),
            'edit' => Pages\EditMailLog::route('/{record}/edit'),
        ];
    }
}
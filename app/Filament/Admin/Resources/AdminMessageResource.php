<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminMessageResource\Pages;
use App\Filament\Admin\Resources\AdminMessageResource\RelationManagers;
use App\Models\AdminMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminMessageResource extends Resource
{
    protected static ?string $model = AdminMessage::class;

    
    protected static ?string $navigationIcon = 'heroicon-o-mail';
    protected static ?string $navigationGroup = 'الإدارة';
    protected static ?string $navigationLabel = 'الرسائل الواردة';
    protected static ?string $slug = 'admin-messages';
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Admin'); // التأكد من أن المستخدم أدمن
    }
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
            TextColumn::make('sender')->label('المرسل')->sortable()->searchable(),
            TextColumn::make('subject')->label('الموضوع')->sortable()->searchable(),
            TextColumn::make('received_at')->label('تاريخ الاستلام')->sortable(),
        ])
            ->filters([
                Filter::make('today')->label('رسائل اليوم')->query(fn ($query) => $query->whereDate('received_at', today())),

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
            'index' => Pages\ListAdminMessages::route('/'),
            'create' => Pages\CreateAdminMessage::route('/create'),
            'edit' => Pages\EditAdminMessage::route('/{record}/edit'),
        ];
    }
}
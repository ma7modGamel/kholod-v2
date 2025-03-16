<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ArchivedLeaveRequest;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ArchivedLeaveRequestResource\Pages;
use App\Filament\Admin\Resources\ArchivedLeaveRequestResource\RelationManagers;

class ArchivedLeaveRequestResource extends Resource
{
    protected static ?string $model = ArchivedLeaveRequest::class;

 
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'الطلبات العامة';
    protected static ?string $navigationLabel = 'الطلبات المنتهيه';
    protected static ?string $pluralModelLabel = 'الطلبات المنتهية';
    protected static ?string $modelLabel = 'طلب منتهي';

  

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('leave_request_id')->label('رقم طلب الإجازة'),
            Tables\Columns\TextColumn::make('user.name')->label('الموظف'),
            Tables\Columns\TextColumn::make('status')
                ->label('الحالة')
                ->formatStateUsing(fn ($state) => $state == -1 ? 'مرفوض' : 'موافقة نهائية')
                ->badge()
                ->colors([
                    'danger' => fn ($state) => $state == -1,
                    'success' => fn ($state) => $state == 2,
                ]),
            Tables\Columns\TextColumn::make('processed_at')->label('تاريخ الإجراء')->dateTime(),
        ])
            ->filters([
                SelectFilter::make('status')
                ->label('تصفية حسب الحالة')
                ->options([
                    -1 => 'مرفوض',
                    2 => 'موافقة نهائية',
                ]),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListArchivedLeaveRequests::route('/'),
            // 'create' => Pages\CreateArchivedLeaveRequest::route('/create'),
            'edit' => Pages\EditArchivedLeaveRequest::route('/{record}/edit'),
        ];
    }
}
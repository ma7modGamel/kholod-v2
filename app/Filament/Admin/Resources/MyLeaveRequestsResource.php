<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LeaveRequest;
use Pages\ListLeaveRequests;
use Pages\CreateLeaveRequest;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\MyLeaveRequestsResource\Pages;
use App\Filament\Admin\Resources\MyLeaveRequestsResource\RelationManagers;
use App\Filament\Admin\Resources\MyLeaveRequestsResource\Pages\ListMyLeaveRequests;

class MyLeaveRequestsResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'الطلبات العامة';
    protected static ?string $modelLabel = 'طلباتي';
    protected static ?string $pluralModelLabel = 'طلباتي';
    protected static ?string $createButtonLabel = 'إضافة طلب جديد';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
      
        ->where('employee_id', auth()->id());
        // dd();
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('leave_type')->label('نوع الإجازة'),
                Tables\Columns\TextColumn::make('start_date')->label('تاريخ البداية'),
                Tables\Columns\TextColumn::make('end_date')->label('تاريخ النهاية'),
                Tables\Columns\TextColumn::make('notes')->label('الملاحظات '),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ الانشاء '),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (?string $state): string => match ((int)($state ?? 0)) {
                        0 => 'gray',
                        1 => 'warning',
                        2 => 'success',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => match ((int)($state ?? 0)) {
                        0 => 'في الانتظار',
                        1 => 'موافقة مؤقتة',
                        2 => 'موافقة نهائية',
                        default => 'مرفوض',
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => in_array($record->status, [0, 1])),
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
            'index' => ListMyLeaveRequests::route('/'),
            // 'create' => Pages\CreateLeaveRequest::route('/create'),
            // 'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
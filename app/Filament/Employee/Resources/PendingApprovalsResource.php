<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\PendingApprovalsResource\Pages;
use App\Filament\Employee\Resources\PendingApprovalsResource\RelationManagers;
use App\Models\PendingApprovals;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendingApprovalsResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;
  
    protected static ?string $navigationGroup = 'الطلبات العامة';
    protected static ?string $modelLabel = 'طلبات الموافقة';
    protected static ?string $pluralModelLabel = 'طلبات الموافقة';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([ 'approvers.user', 'latestApprover']) 
            ->where(function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('employeemanager_id', auth()->id()) 
                          ->orWhere('id', auth()->id()); 
                })
                ->orWhereHas('approvers', function ($q) {
                    $q->where('user_id', auth()->id()); 
                });
            })
            ->where('employee_id', '!=', auth()->id())
        
            ->whereNotIn('status', [-1, 2]); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')->label('الموظف'),
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
                ->visible(fn ($record) => 
                in_array($record->status, [0, 1]) && 
                (optional($record->employee)->employeemanager_id === auth()->id() 
                || $record->approvers()->where('user_id', auth()->id())->exists())
            )
                    ->visible(fn ($record) => 
                        optional($record->employee)->employeemanager_id === auth()->id() 
                        || $record->approvers()->where('user_id', auth()->id())->exists()
                    ),
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
            'index' => Pages\ListPendingApprovals::route('/'),
            // 'create' => Pages\CreatePendingApprovals::route('/create'),
            // 'edit' => Pages\EditPendingApprovals::route('/{record}/edit'),
        ];
    }
}
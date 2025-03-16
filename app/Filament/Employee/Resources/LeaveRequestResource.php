<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\LeaveRequestResource\Pages;
use App\Filament\Employee\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Filters\TabsFilter;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Employee\Resources\LeaveRequestResource\RelationManagers\ApproversRelationManager;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'الطلبات العامة';
    protected static ?string $modelLabel =' طلبات الاجازة ';
    protected static ?string $label = 'طلب الاجازه';
    protected static ?string $pluralModelLabel = 'طلبات الاجازه';
    protected static ?string $createButtonLabel = 'اضافه طلب';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                ->label('الموظف')
                ->options(\App\Models\User::pluck('name', 'id'))
                ->default(auth()->id())
                ->hidden()
                ->required(),
            Forms\Components\Select::make('leave_type')
                ->label('نوع الإجازة')
                ->options([
                    'سنوية' => 'سنوية',
                    'مرضية' => 'مرضية',
                    'طارئة' => 'طارئة',
                ])
                ->required(),
                Forms\Components\TextInput::make('status')
                ->label('الحالة ')
                ->default('الانتظار')
                ->disabled()
                ->required(),
            Forms\Components\DatePicker::make('start_date')->label('تاريخ البداية')->required(),
            Forms\Components\DatePicker::make('end_date')->label('تاريخ النهاية')->required(),
            Forms\Components\Textarea::make('notes')->label('ملاحظات')->nullable(),

        
        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')->label('الموظف'),
                Tables\Columns\TextColumn::make('employee.manager.name')->label('المدير المباشر'),
                Tables\Columns\TextColumn::make('leave_type')->label('نوع الإجازة'),
                Tables\Columns\TextColumn::make('start_date')->label('تاريخ البداية'),
                Tables\Columns\TextColumn::make('end_date')->label('تاريخ النهاية'),
                Tables\Columns\TextColumn::make('notes')->label('الملاحظات '),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ الانشاء '),
                Tables\Columns\TextColumn::make('latestApprover.user.name')
                ->label('تم إرسال الطلب إلى')
                ->formatStateUsing(fn ($record) => 
                    $record->approvers->last()?->user?->name ?? 'غير معروف'
                ),
                Tables\Columns\ImageColumn::make('latestApprover.user.signature')
                
                    ->label('التوقيع')
                    ->height(40)
                    ->defaultImageUrl('/images/default-signature.png'),

                Tables\Columns\TextColumn::make('status')->label('الحالة')
                
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
                })
                ->default('في الانتظار'),
            
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->visible(fn ($record) => 
                in_array($record->status, [0, 1]) && 
                (optional($record->approvers()->where('user_id', auth()->id())->exists()) 
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
            ApproversRelationManager::class,

        ];
    }
//     public static function getEloquentQuery(): Builder
// {
//     return parent::getEloquentQuery()
//         ->with([ 'approvers.user', 'latestApprover','employee']) 
//         ->where(function ($query) {
//             $query->whereHas('employee', function ($q) {
//                 $q->where('employeemanager_id', auth()->id()) 
//                       ->orWhere('id', auth()->id()); 
//             })
//             ->orWhereHas('approvers', function ($q) {
//                 $q->where('user_id', auth()->id()); 
//             })
//             ->orWhere(function ($query) {
//                 $query->whereHas('employee', function ($q) {
//                     $q->whereNull('employeemanager_id');
//                 })
//                 ->whereHas('employee.titles', function ($q) {
//                     $q->where('slug', 'Human_r_manager');
//                 });
//             });
//         });
//         // ->where('employee_id', '!=', auth()->id())
    
//         // ->whereNotIn('status', [-1, 2]); 
// }

public static function getEloquentQuery(): Builder
{
    $isHrManager = auth()->user()->titles?->slug === 'Human_r_manager';

    return parent::getEloquentQuery()
        ->with(['approvers.user', 'latestApprover', 'employee'])
        ->where(function ($query) use ($isHrManager) {
            $query->whereHas('employee', function ($q) {
                $q->where('employeemanager_id', auth()->id())
                  ->orWhere('id', auth()->id()); 
            })
            ->orWhereHas('approvers', function ($q) {
                $q->where('user_id', auth()->id()); 
            });

            if ($isHrManager) {
                $query->orWhereHas('employee', function ($q) {
                    $q->whereNull('employeemanager_id');
                });
            }
        });
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
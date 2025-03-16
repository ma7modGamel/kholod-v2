<?php

namespace App\Filament\Admin\Resources\LeaveRequestResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LeaveRequestStatus;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class StatusesRelationManager extends RelationManager
{
    protected static string $relationship = 'statuses';
    protected static ?string $label = 'حالة طلب الإجازة'; 
    protected static ?string $modelLabel = 'حالة طلب الإجازة';
    protected static ?string $pluralModelLabel = 'حالات طلب الإجازة';
    protected static ?string $createButtonLabel = 'إضافة حالة';
    public static function getTitle(Model $ownerRecord, string $pageClass): string 
    {
        return 'حالات طلب الإجازة';
    }
    
    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('status')
                ->options([
                    'approved' => 'موافقة',
                    'rejected' => 'رفض',
                ])
                ->required()
                ->default('pending')
                ->label('تغيير الحالة')
                ->visible(fn () => $this->isCurrentApprover()), 
                
            Forms\Components\Textarea::make('notes')
                ->label('الملاحظات')
                ->rows(4)
                ->nullable()
                ->visible(fn () => $this->isCurrentApprover()),   
                // Select::make('approver_id')
                // ->label('الموافِق التالي')
                // ->options(\App\Models\User::pluck('name', 'id')) // عرض جميع المستخدمين للاختيار
                // ->searchable()
                // ->nullable()
                // ->visible(fn ($record) => $record && $record->status === 'approved'), 
            
            Hidden::make('approver_id')
                ->default(auth()->id()),    
        ]);
    }
    
    public function isVisible(): bool
    {
        return $this->getOwnerRecord()->employee->employeemanager_id === auth()->id();
    }
   
    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             Tables\Columns\TextColumn::make('approver.name')->label('المدير'),
    //             Tables\Columns\TextColumn::make('status')->label('الحالة')
    //                 ->badge()
    //                 ->color(fn (string $state): string => match ($state) {
    //                     'pending' => 'warning',
    //                     'approved' => 'success',
    //                     'rejected' => 'danger',
    //                     default => 'gray',
    //                 }),
    //             Tables\Columns\TextColumn::make('notes')->label('الملاحظات'),
    //             Tables\Columns\TextColumn::make('approver.name')->label('الموافق التالي')->sortable(),

    //             Tables\Columns\TextColumn::make('created_at')->label('تاريخ التحديث')->dateTime(),
    //         ])
    //         ->headerActions([
    //             Tables\Actions\CreateAction::make()
    //             ->visible(fn () => $this->isCurrentApprover() && $this->getOwnerRecord()->statuses()->count() == 0), 
    //                 // ->visible(fn () => $this->isCurrentApprover()), 
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make()
    //             ->visible(fn () => $this->isCurrentApprover() && $this->getOwnerRecord()->statuses()->count() == 0), 
    //                 // ->visible(fn ($record) => $this->isCurrentApprover()), 
    //             Tables\Actions\DeleteAction::make()
    //             // ->visible(fn () => $this->isCurrentApprover() && $this->getOwnerRecord()->statuses()->count() == 0), 
    //                 ->visible(fn ($record) => $this->isCurrentApprover()), 
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\DeleteBulkAction::make()
    //                 ->visible(fn () => $this->isCurrentApprover()),  
    //         ]);
    // }
    public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('approver.name')->label('الموافق'),
            Tables\Columns\TextColumn::make('status')->label('الحالة')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('notes')->label('الملاحظات'),
            Tables\Columns\TextColumn::make('created_at')->label('تاريخ التحديث')->dateTime(),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->visible(fn () => $this->isCurrentApprover()), 
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->visible(fn () => $this->isCurrentApprover()), 
            Tables\Actions\DeleteAction::make()
                ->visible(fn ($record) => $this->isCurrentApprover()), 
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make()
                ->visible(fn () => $this->isCurrentApprover()),  
        ]);
}

//     private function isCurrentApprover(): bool
// {
//     $owner = $this->getOwnerRecord(); 
//     return $owner->employee->employeemanager_id === auth()->id(); 
// }
private function isCurrentApprover(): bool
{
    $owner = $this->getOwnerRecord();
    return $owner->employee->employeemanager_id === auth()->id()
        || $owner->statuses()->latest()->first()?->next_approver_id === auth()->id();
}
// private function isCurrentApprover(): bool
// {
//     $owner = $this->getOwnerRecord();  
//     $currentUserId = auth()->id();  
//     if ($owner->employee->employeemanager_id === $currentUserId) {
//         return true;
//     }

//     $nextApprover = $owner->approvers()
//         ->whereNull('status') 
//         ->orderBy('order', 'asc')  
//         ->first();

//     return $nextApprover && $nextApprover->user_id === $currentUserId;
// }




  
}
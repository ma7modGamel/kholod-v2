<?php

namespace App\Filament\Employee\Resources\LeaveRequestResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ApproversRelationManager extends RelationManager
{
    protected static string $relationship = 'approvers';
    protected static ?string $label = 'حالة طلب الإجازة'; 
    protected static ?string $modelLabel = 'حالة طلب الإجازة';
    protected static ?string $pluralModelLabel = 'حالات طلب الإجازة';
    protected static ?string $createButtonLabel = 'إضافة حالة';

    public static function getTitle(Model $ownerRecord, string $pageClass): string 
    {
        return 'تحويل طلب الاجازه';
    }
    public function form(Form $form): Form
    {
      
        return $form->schema([
            Forms\Components\Select::make('user_id')
            ->label('الموظف الموافق')
            ->relationship('user', 'name')
            ->searchable()
            ->preload()
            ->required()
            ->live()
            ->afterStateUpdated(function (Forms\Set $set, $state) {
                if ($user = User::find($state)) {
                    $set('title_id', $user->title_id);
                    $set('name', $user->name);
                }
            }),

            Forms\Components\Select::make('title_id')
                ->label('المسمى الوظيفي')
                ->options(Title::pluck('name', 'id'))
                ->disabled()
                ->dehydrated(),
                Forms\Components\Textarea::make('notes')->label('ملاحظات')->nullable(),

        ]);
    }

  
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ApproversRelationManager')
            ->columns([
                
                Tables\Columns\TextColumn::make('leaveRequest.employee.name')
                ->label('الموظف مقدم الطلب'),
                
                Tables\Columns\TextColumn::make('user.name')->label('المدير'),
                Tables\Columns\TextColumn::make('title.name')->label('المسمى الوظيفي'),
                Tables\Columns\TextColumn::make('notes')->label('الملاحظات '),
                Tables\Columns\TextColumn::make('updated_at')->label('تاريخ الانشاء '),
                Tables\Columns\TextColumn::make('user.name')
                ->label('تم إرسال الطلب إلى')
                ->formatStateUsing(fn ($record) => $record->user?->name ?? 'غير معروف'),
            
            
                Tables\Columns\TextColumn::make('status')
                ->label('الحالة')
                ->default('في الانتظار')
                ->formatStateUsing(fn ($record) => $this->formatStatusWithUser($record)),
               
             ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->visible(fn () => $this->canAddApprover() && !$this->isRequestOwner() && !$this->isRequestFinalizedOrRejected())

                // ->visible(fn () => $this->canAddApprover() && !$this->isRequestOwner())
                ->disableCreateAnother()
              
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                ->label('الموافقة المؤقتة')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn ($record) => $this->isCurrentApprover($record)) 
                ->action(function ($record) {
                    $record->update([
                        'status' => 1,
                        'user_id' => auth()->id(),]);
                }),
            
                Tables\Actions\Action::make('reject')
                ->label('رفض')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn ($record) => $this->isCurrentApprover($record)) 
                ->action(function ($record) {
                    $leaveRequest = $this->getOwnerRecord();
                    $record->update([
                        'status' => -1,
                        'user_id' => auth()->id(),
                    ]);
                    $leaveRequest->update(['status' => -1]);
                    \App\Models\ArchivedLeaveRequest::create([
                        'leave_request_id' => $leaveRequest->id,
                        'user_id' => auth()->id(),
                        'status' => -1,
                        'processed_at' => now(),
                    ]);
                }),
                Tables\Actions\Action::make('finalize')
                ->label('إنهاء')
                ->icon('heroicon-o-check-badge')
                ->color('primary')
                ->visible(fn ($record) => $this->canFinalize())
                ->action(function ($record) {
                    $leaveRequest = $this->getOwnerRecord();
                    $record->update([
                        'status' => 2,
                        'user_id' => auth()->id(),
                    ]);
                    $leaveRequest->update([
                        'status' => 2,
                        'user_id' => auth()->id(),
                        'final_approved_at' => now(),
                    ]);
                    \App\Models\ArchivedLeaveRequest::create([
                        'leave_request_id' => $leaveRequest->id,
                        'user_id' => auth()->id(),
                        'status' => 2, 
                        'processed_at' => now(),
                    ]);
                }),
            
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    private function isCurrentApprover($record): bool
    {
        $leaveRequest = $this->getOwnerRecord();
        $currentUserId = auth()->id();
        return $leaveRequest->approvers()
            ->where('user_id', $currentUserId)
            ->where('id', $record->id)
            ->whereRaw('COALESCE(status, 0) = 0') 
            ->exists();
    }
    

    private function canFinalize(): bool
    {
        $leaveRequest = $this->getOwnerRecord();
        $totalApprovers = $leaveRequest->approvers()->count();
        $approvedCount = $leaveRequest->approvers()->where('status', 1)->count();
        return $totalApprovers > 0 && $approvedCount === $totalApprovers;
    }
    private function isRequestOwner(): bool
{
    $leaveRequest = $this->getOwnerRecord(); 
    return auth()->id() === $leaveRequest->employee_id;
}

private function isRequestFinalizedOrRejected(): bool
{
    $leaveRequest = $this->getOwnerRecord();
    return in_array($leaveRequest->status, [1, 2, -1]); 
}
private function canAddApprover(): bool
{
    $leaveRequest = $this->getOwnerRecord();
    $currentUser = auth()->user();
    if ($leaveRequest->approvers()->count() === 0) {
        return true;
    }
    if ($currentUser->id === $leaveRequest->manager_id && $leaveRequest->status === 1) {
        return true;
    }

    if ($leaveRequest->approvers()->where('user_id', $currentUser->id)->exists()) {
        return true;
    }
   
    return in_array($leaveRequest->status, [-1, 2]);
}
private function formatStatusWithUser($record): string
{
    $statusText = match (intval($record->status ?? 0)) {
        0 => 'في الانتظار',
        1 => 'تمت الموافقة المؤقتة من قبل ',
        2 => 'تمت الموافقة النهائية من قبل ',
        -1 => 'تم الرفض من قبل ',
        default => 'في الانتظار',
    };

    $approverName = $record->user?->name ?? 'غير معروف'; 
    return in_array($record->status, [1, 2, -1]) ? $statusText . $approverName : $statusText;
}

}
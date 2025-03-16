<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\LeaveRequestResource\Pages;
use App\Filament\SuperAdmin\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Collection;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'ادارة طلبات الموظفين ';
    protected static ?string $modelLabel ='  ادارة طلبات الاجازة  ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Repeater::make('approvers')
                    ->schema(self::getEmployeeRepeaterSchema('leave_management'))
                    ->label('ترتيب الإداريين للموافقة')
                    // ->defaultItems(1)
                    ->columnSpanFull(), // اجعل الحقل يأخذ كامل العرض
                    
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
          
            Tables\Columns\TextColumn::make('approvers.admin.name')
                ->label('Approvers')
                ->formatStateUsing(function ($state) {
                    return collect($state)->implode(', ');
                }),
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
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }

    private static function getEmployeeRepeaterSchema(string $managementType): array
    {
        return [
            Select::make('title_id')
                ->relationship('titles', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->live()
                ->label(__('المسمي الوطيفي')),
             
                

            Select::make('employee_id')
                ->label(__('الموظفون'))
                ->options(fn(Get $get): Collection => User::query()
                    ->where('title_id', $get('title_id'))
                    ->pluck('name', 'id')),
          
            TextInput::make('order')
                ->required()
                ->numeric()
                ->minValue(1)
                ->label('الترتيب '),
             


            Forms\Components\Hidden::make('management_type')
                ->default($managementType),
        ];
    }
}
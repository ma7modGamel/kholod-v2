<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\CorrespondenceResource\Pages;
use App\Filament\SuperAdmin\Resources\CorrespondenceResource\RelationManagers;
use App\Filament\SuperAdmin\Resources\CorrespondenceResource\RelationManagers\TrackingsRelationManager;
use App\Models\Correspondence;
use App\Models\CorrespondenceDocuments;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CorrespondenceResource extends Resource
{
    protected static ?string $model = Correspondence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = ' إحالة ';
    protected static ?string $pluralModelLabel = '  الإحالات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->numeric()
                    ->label('رقم المستند')
                    ->maxLength(255),
                DatePicker::make('date')
                    ->required()
                    ->label('تاريخ المستند'),
                TextInput::make('correspondent_id')
                    ->required()
                    ->label('الجهه ')
                    ->formatStateUsing(function ($record) {
                        return $record->correspondent?->modelable?->name;
                    }),
                Select::make('correspondence_document_id')
                    ->required()
                    ->label('نوع المستند')
                    ->relationship('correspondence_document', 'type')
                    ->live(),
                Forms\Components\TextInput::make('total_value')
                    ->numeric()
                    ->label('القيمة ')->columnSpanFull()->required()
                    ->visible(
                        fn($record, $get) => CorrespondenceDocuments::query()
                            ->where([
                                'id' => $get('correspondence_document_id'),
                                'need_total_value' => 1
                            ])->exists()
                    ),
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required()
                    ->preload()
                    ->columnSpan('full')
                    ->label('المشروع'),
                Textarea::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('file')
                    ->label('الملف')
                    ->openable()->downloadable()
                    ->columnSpanFull()
                    ->directory('correspondences'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable()
                    ->label('رقم المستند'),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('المشروع')->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable()
                    ->label('تاريخ المستند')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('تاريخ الانشاء')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('correspondent_id')
                    ->label('الجهه ')
                    ->formatStateUsing(function ($record) {
                        return $record->correspondent?->modelable->name ??"غير محدد";
                    }),
                Tables\Columns\TextColumn::make('correspondence_document_id')
                    ->label('نوع المستند')
                    ->formatStateUsing(function ($record) {
                        return $record->correspondence_document?->type;
                    }),
                Tables\Columns\TextColumn::make('project_id')
                    ->label('حالة المستند')
                    ->formatStateUsing(function ($record) {
                        if ($record->trackings->isEmpty()) {
                            return 'جديد';
                        }else{
                            return $record->trackings->last()->toUser->name??"غير محدد";
                        }

                    }),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            TrackingsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCorrespondences::route('/'),
            'view' => Pages\ViewCorrespondence::route('/{record}'),

        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->whereHas('trackings')
        ->orWhereHas('correspondence_document', function ($builder) {
            $builder->where(function ($query) {
                $query->where('type', 'LIKE', '%عقود%')
                      ->orWhere('type', 'LIKE', '%عقد%')
                      ->orWhere('type', 'LIKE', '%تعميدات%');
            });
        });
    }
}
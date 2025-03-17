<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EmailResource\Pages;
use App\Filament\Admin\Resources\EmailResource\RelationManagers;
use App\Models\Email;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class EmailResource extends Resource
{
    protected static ?string $model = Email::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subject')->label('Subject'),
                Forms\Components\TextInput::make('from')->label('From'),
                Forms\Components\TextInput::make('to')->label('To'),
                Forms\Components\Textarea::make('body')->label('Body'),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')->label('Subject')->searchable(),
                Tables\Columns\TextColumn::make('from')->label('From')->searchable(),
                Tables\Columns\TextColumn::make('received_at')->label('Received At')->dateTime(),
                Tables\Columns\IconColumn::make('is_read') // استخدام IconColumn بدل TextColumn
                ->label('Read')
                ->boolean(), // عرض true/false كأيقونات
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->form([
                        Forms\Components\TextInput::make('to')->default(fn ($record) => $record->from),
                        Forms\Components\TextInput::make('subject')->default(fn ($record) => 'Re: ' . $record->subject),
                        Forms\Components\Textarea::make('body')->required(),
                    ])
                    ->action(function (array $data) {
                        Mail::raw($data['body'], function ($message) use ($data) {
                            $message->to($data['to'])
                                    ->subject($data['subject']);
                        });

                        Email::create([
                            'message_id' => Str::uuid()->toString(), // توليد message_id فريد
                            'from' => 'replay@kholood.com',
                            'to' => $data['to'],
                            'subject' => $data['subject'],
                            'body' => $data['body'],
                            'date' => now(),
                            'user_id' => auth()->id(),
                        ]);

                    }),
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
            'index' => Pages\ListEmails::route('/'),
            'create' => Pages\CreateEmail::route('/create'),
            'edit' => Pages\EditEmail::route('/{record}/edit'),
        ];
    }
}

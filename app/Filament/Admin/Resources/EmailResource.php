<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EmailResource\Pages;
use App\Models\Email;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\User;
use Illuminate\Support\Str;
use Filament\Notifications\Notification; // ضيفي ده في الـ use statements
use Illuminate\Support\Facades\Mail as MailFacade;
use Vormkracht10\Mails\Models\Mail as MailModel; // الـ Namespace الصحيح من laravel-mails

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EmailResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail as MailFacade;
use Vormkracht10\Mails\Models\Mail as MailModel;

class EmailResource extends Resource
{
    protected static ?string $model = MailModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'Inbox';
    protected static ?string $navigationGroup = 'Emails';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subject')->label('Subject'),
                Forms\Components\TextInput::make('from')->label('From'),
                Forms\Components\TextInput::make('to')->label('To'),
                Forms\Components\Textarea::make('text')->label('Body'), // استخدم 'text' بدلاً من 'body'
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')->label('Subject')->searchable(),
                Tables\Columns\TextColumn::make('from')->label('From')->searchable(),
                Tables\Columns\TextColumn::make('sent_at')->label('Sent At')->dateTime(), // استخدم sent_at
                Tables\Columns\IconColumn::make('is_read')->label('Read')->boolean(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->label('Reply')
                    ->form([
                        Forms\Components\TextInput::make('to')->default([self::class, 'getDefaultTo']),
                        Forms\Components\TextInput::make('subject')->default([self::class, 'getDefaultSubject']),
                        Forms\Components\Textarea::make('body')->required(), // استخدم 'text'
                    ])
                    ->action(function (array $data) {
                        $user = auth()->user(); //
                        MailFacade::raw($data['body'], function ($message) use ($data) {
                            $message->to($data['to'])
                                    ->subject($data['subject'])
                                    ->from($user->email, $user->name ?? 'Kholood'); // الإيميل والاسم من بروفايل المستخدم
           
                        });

                        MailModel::create([
                            'uuid' => Str::uuid()->toString(),
                            'mailer' => config('mail.mailer', 'smtp'),
                            'from' => [['email' => $user->email, 'name' => $user->name ?? 'Kholood']], // من بيانات المستخدم
                            'to' => $data['to'], // ممكن تخليه Array لو عايزة JSON زي الـ from
                            'subject' => $data['subject'],
                            'text' => $data['body'], // غيرت 'text' لـ 'body' لأنه اللي في الفورم
                            'html' => nl2br(e($data['body'])),
                            'sent_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'user_id' => optional(auth())->id,
                        
                        ]);
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('compose')
                    ->label('Compose Email')
                    ->form([
                        Forms\Components\TextInput::make('to')->required()->email(),
                        Forms\Components\TextInput::make('subject')->required(),
                        Forms\Components\Textarea::make('body')->required(), // استخدم 'text'
                    ])
                    ->action(function (array $data) {
                        $user = auth()->user(); // تعريف $user هنا داخل الـ action
                        MailFacade::raw($data['body'], function ($message) use ($data) {
                            $message->to($data['to'])
                                    ->subject($data['subject'])
                                    ->from($user->email, $user->name ?? 'Kholood'); // الإيميل والاسم من بروفايل المستخدم
           
                        });

                        MailModel::create([
                            'uuid' => Str::uuid()->toString(),
                            'mailer' => config('mail.mailer', 'smtp'),
                            'from' => [['email' => $user->email, 'name' => $user->name ?? 'Kholood']],
                            'to' => $data['to'], // ممكن تخليه Array لو عايزة JSON زي الـ from
                            'subject' => $data['subject'],
                            'text' => $data['body'], // غيرت 'text' لـ 'body' لأنه اللي في الفورم
                            'html' => nl2br(e($data['body'])),
                            'sent_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'user_id' => optional(auth())->id,
                        ]);

                        Notification::make()
                            ->title('تم إرسال البريد بنجاح')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getDefaultTo($record): ?string
    {
        return $record->from[0]['email'] ?? ''; // تعديل للتعامل مع مصفوفة from
    }

    public static function getDefaultSubject($record): ?string
    {
        return isset($record->subject) ? 'Re: ' . $record->subject : 'Re:';
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmails::route('/'),
            'edit' => Pages\EditEmail::route('/{record}/edit'),
        ];
    }
}
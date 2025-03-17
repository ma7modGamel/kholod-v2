<?php

namespace App\Filament\Admin\Resources\ComposeEmailResource\Pages;

use App\Filament\Admin\Resources\ComposeEmailResource;
use Filament\Resources\Pages\Page;

class ComposeEmail extends Page
{
    protected static string $resource = ComposeEmailResource::class;

    protected static string $view = 'filament.admin.resources.compose-email-resource.pages.compose-email';

   // protected static string $view = 'filament.pages.compose-email';

    public $to;
    public $subject;
    public $body;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('to')->required()->email(),
            Forms\Components\TextInput::make('subject')->required(),
            Forms\Components\Textarea::make('body')->required(),
        ];
    }

    public function send()
    {
        Mail::raw($this->body, function ($message) {
            $message->to($this->to)
                    ->subject($this->subject);
        });

        $this->notify('success', 'Email sent successfully!');
        $this->reset(['to', 'subject', 'body']);
    }
}
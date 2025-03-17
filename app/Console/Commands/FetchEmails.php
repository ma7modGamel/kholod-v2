<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use App\Models\AdminMessage;


class FetchEmails extends Command
{
    protected $signature = 'emails:fetch';
    protected $description = 'Fetch emails from the server';

    public function handle()
    {
        $client = Client::account('default');
        $client->connect();
        $folder = $client->getFolder('INBOX');
        $messages = $folder->messages()->all()->get();

        foreach ($messages as $message) {
            Email::updateOrCreate(
                ['message_id' => $message->getMessageId()],
                [
                    'subject' => $message->getSubject(),
                    'from' => $message->getFrom()[0]->mail,
                    'to' => $message->getTo()[0]->mail,
                    'body' => $message->getTextBody(),
                    'received_at' => $message->getDate(),
                ]
            );
            
            $message->setFlag(['Seen']); // وضع علامة "مقروء"
        }

        $this->info('Emails fetched successfully!');
        

        $client->disconnect();
    }
}
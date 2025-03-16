<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use App\Models\AdminMessage;

class FetchEmails extends Command
{
    protected $signature = 'emails:fetch';
    protected $description = 'Fetch new emails from the admin email inbox';

    public function handle()
    {
        $client = Client::account('default');
        $client->connect();

        $folder = $client->getFolder('INBOX');
        $messages = $folder->messages()->unseen()->get();

        foreach ($messages as $message) {
            AdminMessage::create([
                'sender' => $message->getFrom()[0]->mail,
                'subject' => $message->getSubject(),
                'body' => $message->getTextBody(),
                'received_at' => $message->getDate(),
            ]);

            $message->setFlag(['Seen']); // وضع علامة "مقروء"
        }

        $this->info('Emails fetched successfully!');
    }
}
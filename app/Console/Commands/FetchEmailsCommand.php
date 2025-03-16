<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webklex\IMAP\Facades\Client;
use App\Models\Email;
class FetchEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   
    protected $description = 'Fetch emails from Webmail and store them in the database';
    protected $signature = 'app:fetch-emails-command';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        $client = Client::account('default'); // استخدام إعدادات IMAP من .env
        $client->connect();

        $folder = $client->getFolder('INBOX'); // تحديد صندوق الوارد
        $messages = $folder->messages()->limit(10)->get(); // جلب آخر 10 رسائل

        foreach ($messages as $message) {
            Email::updateOrCreate(
                ['message_id' => $message->getMessageId()],
                [
                    'from' => $message->getFrom()[0]->mail,
                    'subject' => $message->getSubject(),
                    'body' => $message->getTextBody(),
                    'date' => $message->getDate(),
                ]
            );
        }

        $this->info("Emails fetched successfully!");
    
    }
}
<?php


use App\Models\User;
use App\Mail\TestMail;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Mailer;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Mailer\Transport;
use App\Http\Controllers\TestController;
use App\Http\Controllers\generatePDFController;
use App\Notifications\UserApprovedNotification;
use Vormkracht10\FilamentMails\Facades\FilamentMails;
FilamentMails::routes();
Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('welcome');
})->name('login');


Route::get('/regiser', function () {
    return view('filament.register');
})->name('panel.register');

Route::get('/registerRequest', function () {
    $user = User::find(10);
    // $approved = $this->data['approved'];

    if ($user->approved) {
        // dd($user);

         $user->notify(new UserApprovedNotification);
        //   return $re
    }
})->name('registerRequest');


Route::prefix('generate-pdf')->name('generate-pdf.')
    ->group(function () {
            Route::get('/{record}', [generatePDFController::class,'orderReport'])->name('order.report'); // order Reports
            Route::get('correspondence/{record}', [generatePDFController::class,'correspondenceReport'])->name('correspondence.report'); // order Reports

    });
Route::get('new-pdf/{record}',[generatePDFController::class,'testPdf'])->name('new.pdf');
Route::get('test', [TestController::class, 'test']);



// Route::get('/test-mail', function () {
//     Mail::raw('هذا اختبار MailHog', function ($message) {
//         $message->to('test@example.com')
//             ->subject('اختبار MailHog من Laravel');
//     });

//     return 'تم إرسال البريد! تحقق من MailHog في http://localhost:8025';
// });
//         return 'تم إرسال البريد بنجاح! تحققي من MailHog على http://localhost:8025';
//     } catch (\Exception $e) {
//         return 'خطأ في إرسال البريد: ' . $e->getMessage();
//     }
// });
// Route::get('/test-mail', function () {
//     try {
//         Mail::raw('هذا بريد تجريبي!', function ($message) {
//             $message->to('test@example.com')
//                 ->from('noreply@kholood.com')
//                 ->subject('اختبار الإرسال');
//         });

        
//         MailLog::create([
//             'subject' => 'اختبار الإرسال',
//             'body' => 'هذا بريد تجريبي!',
//             'recipient' => 'test@example.com',
//             'sender' => 'noreply@kholood.com',
//             'status' => 'sent',
//         ]);

//         return 'تم إرسال البريد بنجاح!';
//     } catch (\Exception $e) {
//         return 'خطأ في إرسال البريد: ' . $e->getMessage();
//     }
// }); 

// Route::get('/test-mail', function () {
//     try {
//         $transport = Transport::fromDsn('smtp://localhost:1025');
//         $mailer = new Mailer($transport);
        
//         $email = (new Email())
//             ->from('test@local.com')
//             ->to('test@example.com')
//             ->subject('اختبار الإرسال')
//             ->text('هذا بريد تجريبي!');
        
//         $mailer->send($email);
        
//         return 'تم إرسال البريد بنجاح! تحققي من MailHog على http://localhost:8025';
//     } catch (\Exception $e) {
//         return 'خطأ في إرسال البريد: ' . $e->getMessage();
//     }
// }); 
Route::get('/test-mail', function () {
    return TestMail::sendMail();
});

 Route::get('/debug-mail', function () {
     dd([
         'MAIL_MAILER' => env('MAIL_MAILER'),
         'MAIL_HOST' => env('MAIL_HOST'),
         'MAIL_PORT' => env('MAIL_PORT'),
         'MAIL_USERNAME' => env('MAIL_USERNAME'),
         'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
         'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
     ]);
 });

 Route::get('/check-mail-config', function () {
    return response()->json(config('mail'));
});
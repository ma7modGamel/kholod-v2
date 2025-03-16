<?php
namespace App\Mail;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Illuminate\Support\Facades\DB;

class TestMail
{
    public static function sendMail()
    {
        try {
            // إعداد الاتصال باستخدام DSN
            $transport = Transport::fromDsn('smtp://localhost:1025');
            $mailer = new Mailer($transport);

            // إنشاء البريد
            $email = (new Email())
                ->from('noreply@kholood.com')
                ->to('test@example.com')
                ->subject('اختبار الإرسال')
                ->text('هذا بريد تجريبي!');

            // إرسال البريد
            $mailer->send($email);

            // حفظ في قاعدة البيانات
            DB::table('mail_logs')->insert([
                'subject' => 'اختبار الإرسال',
                'body' => 'هذا بريد تجريبي!',
                'recipient' => 'test@example.com',
                'sender' => 'noreply@kholood.com',
                'status' => 'sent',
                'created_at' => now(),
            ]);

            return 'تم إرسال البريد بنجاح! تحققي من MailHog على http://localhost:8025';

        } catch (\Exception $e) {
            // تسجيل الخطأ في قاعدة البيانات
            DB::table('mail_logs')->insert([
                'subject' => 'اختبار الإرسال',
                'body' => 'هذا بريد تجريبي!',
                'recipient' => 'test@example.com',
                'sender' => 'noreply@kholood.com',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'created_at' => now(),
            ]);

            return 'خطأ في إرسال البريد: ' . $e->getMessage();
        }
    }
}
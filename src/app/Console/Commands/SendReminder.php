<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;
use SendGrid;
use SendGrid\Mail\Mail as SendGridMail;
use Illuminate\Support\Facades\Mail as Mail;
use App\Mail\LocalReminderMail;

class SendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '当日の予約のリマインダーメール送信';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /* 今日の0時から明日の0時までの予約データを取得 */
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $todaysReservations = Reservation::whereBetween('datetime', [$today, $tomorrow])->
            with('user')->get();

        /* 予約者のメールアドレスを配列に格納 */
        $temp = array();
        foreach ($todaysReservations as $item) {
            array_push($temp, $item->user->email);
        }

        /* アドレス重複削除 */
        $addressList = array_unique($temp);

        /* 配列内のアドレスにメール送信 */
        if (app()->isProduction()) {    // 本番環境ではsendgridを使用
            $sendGrid = new SendGrid(config('env.sendgrid_api_key'));
            foreach ($addressList as $address) {
                /* メール編集 */
                $email = new SendGridMail();
                $email->setSubject('予約リマインダーメール');
                $email->setFrom(config('env.mail_from_address'), 'Rese');
                $email->addTo($address);
                $email->addContent('text/plain', "お客様各位\n\nいつもReseをご利用いただき誠にありがとうございます。\n\nこのメールは、本日のご来店予定の予約があるお客様全員にお送りいたしております。\nつきましては、マイページから本日のご予約内容をご確認いただきますよう、よろしくお願い申し上げます。\n\nRese運営事務局");
                
                /* 送信 */
                $sendGrid->send($email);
            }
        } elseif (app()->isLocal()) {   // ローカル環境ではmailhogを使用
            foreach ($addressList as $address) {
                Mail::to($address)->send(new LocalReminderMail());
            }
        }
    }
}

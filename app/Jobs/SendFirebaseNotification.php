<?php

namespace App\Jobs;

use App\Models\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFirebaseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        //
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Authorization: key=' . "AAAAxd68FSg:APA91bGM6cDrNwJQEp2gUcrAXGwFJ3YWYTB75JIgWEAnO7TNl68nhqhembfIiRGib4xqjUlB5ht2K3ONOH-Mi4EelZZzp8QchmAkrjPPi2w4lQl9H9imbrBUenJlTgiHi4VpLFccS2uZ",
            'Content-Type: application/json'
        );
        $tokens = Token::all();
        foreach ($tokens as $key => $token) {

            $fields = array(
                "to" => $token->token_id,
                "notification" => $this->notification,
            );
            $fields = json_encode($fields);
            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            $result = curl_exec($ch);
            // if (curl_exec($ch) === false) {
            //     echo 'Curl error: ' . curl_error($ch);
            // } else {
            //     echo 'notification send';
            // }
            curl_close($ch);
        }
    }
}

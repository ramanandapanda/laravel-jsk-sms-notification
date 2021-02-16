<?php

namespace NotificationChannels\JSKSMS;

use NotificationChannels\JSKSMS\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class JSKSMSChannel
{
    protected $jSKSMSClient;
    public function __construct(JSKSMSClient $jSKSMSClient)
    {
        $this->jSKSMSClient = $jSKSMSClient;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\JSKSMS\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toJSKSMS($notifiable);

        // No jSKSMSMessage object was returned
        if (is_string($message)) {
            $message = JSKSMSMessage::create($message);
        }

        if (! $message->hasToNumber()) {
            if (! $to = $notifiable->phone_number) {
                $to = $notifiable->routeNotificationFor('sms');
            }

            if (! $to) {
                throw CouldNotSendNotification::phoneNumberNotProvided();
            }

            $message->to($to);
        }

        $params = [
            'msg' => $message->getPayloadValue('msg'),
        ];
        $params = array_merge($params,$this->jSKSMSClient->getParams());

        if ($message instanceof JSKSMSMessage) {
            $response = $this->jSKSMSClient->sendMessage($params);
        } else {
            return;
        }
        return json_decode($response->getBody()->getContents(), true);

        // // $response = Http::get('http://jskbulkmarketing.in/app/smsapi/index.php?key=3601A94E7B5B06&entity=&tempid=999999999999999&routeid=569&type=text&contacts=8341668404&senderid=OALERT&msg=Hello+People%2C+have+a+great+day');
        // if (!$response->successful()) { // replace this by the code need to check for errors
        //     throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        // }
    }
}

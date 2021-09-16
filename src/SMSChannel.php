<?php

/**
 * @copyright  2021 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Sam Chen <sam.chen@opencart.cn>
 * @created    2021/8/24 2:23 PM
 * @modified   2021/8/24 2:23 PM
 */

namespace Guangda\Notifications;

use GuzzleHttp\Client;
use \Illuminate\Notifications\Notification as Notification;

class SMSChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $provider = config('sms-notification-channel.provider');
        if (empty($provider)) {
            return;
            // throw new \Exception('No sms.provider specified');
        }

        $message = $notification->toSMS($notifiable);

        if ($provider == 'yunpian') {
            $this->sendByYunpian($notifiable, $notification, $message);
        } elseif ($provider == 'infobip') {
            $this->sendByInfobip($notifiable, $notification, $message);
        }
    }

    protected function getTo($notifiable, $notification)
    {
        if ($notifiable->routeNotificationFor(self::class, $notification)) {
            return $notifiable->routeNotificationFor(self::class, $notification);
        }
        if ($notifiable->routeNotificationFor('sms', $notification)) {
            return $notifiable->routeNotificationFor('sms', $notification);
        }
        if (isset($notifiable->mobile)) {
            return $notifiable->mobile;
        }

        return null;
    }

    protected function sendByYunpian($notifiable, $notification, $message)
    {
        try {
            $client = new Client();
            $res = $client->post('https://sms.yunpian.com/v2/sms/single_send.json', [
                'form_params' => [
                    'apikey' => config('sms-notification-channel.yunpian.key'),
                    'mobile' => $to = $this->getTo($notifiable, $notification), // 只支持国内手机号码
                    'text' => $message->getText(),
                ],
            ]);
            echo $res->getStatusCode();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    protected function sendByInfobip($notifiable, $notification, $message)
    {
        try {
            $key = config('sms-notification-channel.infobip.key');
            $secret = config('sms-notification-channel.infobip.secret');
            $url = config('sms-notification-channel.infobip.url');
            $authorization = 'Basic ' . base64_encode($key . ':' . $secret);

            $client = new Client();

            $headers = [
                'Authorization' => $authorization,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];

            $body = [
                'messages' => [
                    [
                        'from' => config('sms-notification-channel.signature'),
                        'destinations' => [
                            'to' => $this->getTo($notifiable, $notification),
                        ],
                        'text' => $message->getText(),
                    ]
                ],
            ];

            $res = $client->request('POST', $url, [
                'headers' => $headers,
                'json' => $body,
            ]);
            echo $res->getStatusCode();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}

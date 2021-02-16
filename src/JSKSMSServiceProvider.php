<?php

namespace NotificationChannels\JSKSMS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use GuzzleHttp\Client as HttpClient;

class JSKSMSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.

        /**
         * Here's some example code we use for the pusher package.
         *

        $this->app->when(JSKSMSChannel::class)
            ->needs(JSKSMSClient::class)
            ->give(function () {
                $smsConfig = config('services.jsksms');
                return new JSKSMSClient(
                    $smsConfig['key'],
                    $smsConfig['entity'],
                    $smsConfig['tempid'],
                    $smsConfig['routeid'],
                    $smsConfig['type'],
                    $smsConfig['senderid'],
                );
            });
         */

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(JSKSMSClient::class, static function ($app) {
            return new JSKSMSClient(config('services.jsksms'), new HttpClient());
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('jsksms', function ($app) {
                return new JSKSMSChannel($app[JSKSMSClient::class]);
            });
        });
    }
}

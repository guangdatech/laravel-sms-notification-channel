<?php

/**
 * @copyright  2021 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Sam Chen <samchen@opencart.cn>
 * @created    2021/2/13 10:43 PM
 * @modified   2021/2/13 10:43 PM
 */

namespace Guangda\Notifications;

use Illuminate\Support\ServiceProvider;

class SMSChannelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sms-notification-channel.php', 'sms-notification-channel');

        $this->publishes([
            __DIR__.'/../config/sms-notification-channel.php' => config_path('sms-notification-channel.php'),
        ]);
    }
}

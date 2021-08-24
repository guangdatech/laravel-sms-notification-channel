<?php

/**
 * @copyright  2021 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Sam Chen <samchen@opencart.cn>
 * @created    2021/2/8 2:26 PM
 * @modified   2021/2/8 2:26 PM
 */

namespace Guangda\Notifications;

class SMSMessage
{
    public $text = '';

    public function text(string $text): SMSMessage
    {
        $this->text = $text;

        return $this;
    }
}

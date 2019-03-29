<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 18/02/2019
 * Time: 04:35 PM
 */

namespace devent\domain;

use devent\contracts\Event;
use devent\contracts\EventSubscriber;

abstract class Subscriber implements EventSubscriber
{
    /**
     * @param Event $event
     * @return bool
     */
    public function isSubscribedTo($event)
    {
        //Subscrito a todos los eventos
        return true;
    }
}
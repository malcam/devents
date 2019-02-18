<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 18/02/2019
 * Time: 04:35 PM
 */

namespace devent\domain;

use devent\domain\contracts\Event;

class Subscriber implements contracts\EventSubscriber
{
    /**
     * @param Event $event
     */
    public function handle($event)
    {
        echo $event->getBody();
    }

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
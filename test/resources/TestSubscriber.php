<?php
/**
 * Created by PhpStorm.
 * User: asaelel
 * Date: 28/03/2019
 * Time: 10:38 PM
 */
namespace devent\test\resources;

use devent\domain\Subscriber;

class TestSubscriber extends Subscriber
{

    /**
     * @param \devent\contracts\Event $event
     */
    public function handle($event)
    {
        echo $event->body();
    }
}
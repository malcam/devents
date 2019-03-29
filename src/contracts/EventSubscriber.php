<?php
namespace devent\contracts;

interface EventSubscriber
{
    /**
     * @param Event $event
     */
    public function handle($event);

    /**
     * @param Event $event
     * @return bool
     */
    public function isSubscribedTo($event);
}
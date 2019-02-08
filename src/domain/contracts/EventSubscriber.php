<?php
namespace devent\domain\contracts;

interface EventSubscriber
{
    /**
     * @param DomainEvent $event
     */
    public function handle($event);

    /**
     * @param DomainEvent $event
     * @return bool
     */
    public function isSubscribedTo($event);
}
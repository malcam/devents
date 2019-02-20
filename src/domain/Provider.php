<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 20/02/2019
 * Time: 11:09 AM
 */

namespace devent\domain;


class Provider implements \devent\PsrProposal\EventDispatcher\ListenerProviderInterface
{
    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        return [
            function($event) {
                echo $event->body();
            },
            [$this, 'handle']
        ];
    }

    public function handle($event)
    {
        echo $event->body();
    }
}
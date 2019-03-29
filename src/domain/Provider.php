<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 20/02/2019
 * Time: 11:09 AM
 */

namespace devent\domain;

use devent\contracts\EventProvider;

abstract class Provider implements EventProvider
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
//            function($event) {
//                echo $event->body();
//            },
            [$this, 'handle']
        ];
    }

    abstract public function handle(object $event): void;
}
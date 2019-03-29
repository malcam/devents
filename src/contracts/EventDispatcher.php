<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 20/02/2019
 * Time: 01:43 PM
 */

namespace devent\contracts;

use Psr\EventDispatcher\EventDispatcherInterface;

interface EventDispatcher extends EventDispatcherInterface
{
    public function subscribe($subscriber, $topic = '*');

    public function unsubscribe($key);
}
<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 20/02/2019
 * Time: 01:43 PM
 */

namespace devent\domain\contracts;

interface EventDispatcher extends \devent\PsrProposal\EventDispatcher\EventDispatcherInterface
{
    public function subscribe($topic, $subscriber = null);

    public function unsubscribe($key);
}
<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 08/02/2019
 * Time: 04:32 PM
 */
namespace devent\domain\event;

class Dispatcher extends \devent\domain\contracts\Singleton implements \devent\PsrProposal\EventDispatcher
{
    private $subscribers;
    private $index = 0;

    protected function __construct()
    {
        $this->subscribers = [];
    }

    public function get($id)
    { //Add container interface
        return isset($this->subscribers[$id]) ? $this->subscribers[$id] : null;
    }

    public function has($id)
    {
        return isset($this->subscribers[$id]);
    }

    public function subscribe($subscriber)
    {
        $this->subscribers[$this->index] = $subscriber;
        return ++$this->index;
    }

    public function unsubscribe($id)
    {
        unset($this->subscribers[$id]);
    }

    public function dispatch($event)
    {
        foreach ($this->subscribers as $aSubscriber) {
            if ($aSubscriber->isSubscribedTo($event)) {
                $aSubscriber->handle($event);
            }
        }
    }
}
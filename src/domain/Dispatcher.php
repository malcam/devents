<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 08/02/2019
 * Time: 04:32 PM
 */
namespace devent\domain;

class Dispatcher extends \devent\domain\contracts\Singleton implements \devent\PsrProposal\EventDispatcher\EventDispatcherInterface
{
    public static $instance = null;
    private $topics;
    private $lost;
    private $index = 0;

    protected function __construct()
    {
        $this->topics = [];
    }

    public function get($id)
    { //Add container interface
        return isset($this->topics[$id]) ? $this->topics[$id] : null;
    }

    public function has($id)
    {
        return isset($this->topics[$id]);
    }

    public function subscribe($name, $subscriber = null)
    {
        if( null === $subscriber && $name instanceof \EventSubscriber ) {
            $this->topics[] = $subscriber; //normalizar estructura
            $key = key($this->topics);
            $this->lost[] = $key;
            return $key;
        }

        if (!$this->topics[$name]) {
            $$this->topics[$name] = [];
        }

        //validar que sea caleable o implemente tal... process
        $this->topics[$name] = ["index" => ++$this->index, "subscriber" => $subscriber];

        return $this->index;
    }

    public function addSubscriber($subscriber)
    {

    }

    public function addListener($name, $subscriber)
    {

    }

    public function unsubscribe($id)
    {
        unset($this->topics[$id]);
    }

    public function dispatch($event)
    {
        foreach ($this->topics[get_class($event)] as $entry) {
            $this->call($entry, $event);
        }

        foreach ($this->lost as $key) {
            $this->call( $this->topics[$key], $event);
        }
    }

    private function call($subscriber, $event)
    {
        if( $subscriber instanceof \devent\domain\contracts\EventSubscriber && $subscriber->isSubscribedTo($event) ) {
            $subscriber->handle($event);
        }elseif( $subscriber instanceof \Psr\EventDispatcher\ListenerProviderInterface) {
            $collection = $subscriber->getListenersForEvent($event);
            foreach ($collection as $fn ) {
                $this->call($fn);
            }
        }elseif ( is_callable($subscriber, false, $callerClass)) {
            $callerClass->handle($event);
        }
    }
}
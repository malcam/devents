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
    private $unknownTopicKeys;
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

    public function subscribe($topic, $subscriber = null)
    {
        $this->index++;
        //emulando sobrecarga de metodoa tal como subscribe($subscriber)
        if( null === $subscriber && $topic instanceof \EventSubscriber ) {
            $subscriber = $topic;
            $this->unknownTopicKeys[] = $topic = $this->index;
        }

        //validar que sea caleable o implemente tal... process
        $this->topics[$topic] = ["index" => $this->index, "subscriber" => $subscriber];

        return $this->index;
    }

    public function unsubscribe($id)
    {
        unset($this->topics[$id]);
    }

    public function dispatch($event)
    {
        foreach ($this->topics[get_class($event)] as $entry) {
            $this->call($entry['subscriber'], $event);
        }

        foreach ($this->unknownTopicKeys as $key) {
            $this->call( $this->topics[$key]['subscriber'], $event);
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
            $callerClass($event);
        }
    }
}
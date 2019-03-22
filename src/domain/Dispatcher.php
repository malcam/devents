<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 08/02/2019
 * Time: 04:32 PM
 */
namespace devent\domain;

use devent\domain\contracts\EventDispatcher;
use devent\domain\contracts\EventProvider;
use devent\domain\contracts\EventSubscriber;
use devent\domain\contracts\Singleton;

class Dispatcher extends Singleton implements EventDispatcher
{
    public static $instance = null;
    private $topics;
    private $unknownTopicKeys;
    private $index = 0;

    protected function __construct()
    {
        $this->topics = [];
        $this->unknownTopicKeys = [];
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

        if (null === $subscriber) {
            $subscriber = $topic;
            $this->unknownTopicKeys[] = $topic = $this->index;
        }

        $this->assertThatIsSupportedSubscriber($subscriber);

        $this->topics[$topic][] = ["index" => $this->index, "subscriber" => $subscriber];

        return $this->index;
    }

    private function assertThatIsSupportedSubscriber($subscriber)
    {
        if (!$subscriber instanceof EventSubscriber &&
            !$subscriber instanceof EventProvider &&
            !is_callable($subscriber)) {
            //compensasion
            $this->index--;
            array_pop($this->unknownTopicKeys);
            throw new \InvalidArgumentException('El tipo: ' . gettype($subscriber) . ' no es soportado como subscriber');
        }
    }

    public function subscribeV2($topic, $subscriber = null)
    {
        if( null === $subscriber ) {
            $subscriber = $topic;
            $this->unknownTopicKeys[] = $topic = $this->index;
        }

        $fn = $subscriber;
        $this->index++;

        if( $subscriber instanceof EventSubscriber ) {
            $fn = [$subscriber, 'handle'];
        }elseif( $subscriber instanceof  EventProvider ) {
            $fn = function ($event) use ($subscriber) {
                $collection = $subscriber->getListenersForEvent($event);
                $this->callv2($collection, $event);
            };
        }

        if ( !is_callable($fn, false)) {
           throw new \Exception('subscriber no puede ser llamado');
        }

        $this->topics[$topic] = ["index" => $this->index, "subscriber" => $fn];

        return $this->index;
    }

    public function unsubscribe($id)
    {
        unset($this->topics[$id]);
    }

    /**
     * (event) Lanza un evento usando la clase del evento como topic
     * (topic, event) Lanza un evento, especificando un topic
     * @param \devent\domain\contracts\Event|string $topic
     * @param \devent\domain\contracts\Event|null $event
     * @return void
     */
    public function dispatch($topic, $event = null)
    {
        if( null === $event ) {
            $event = $topic;
            $topic = get_class($event);
        }

        if( isset($this->topics[$topic]) ) {
            foreach ($this->topics[$topic] as $entry) {
                $this->call($entry['subscriber'], $event);
            }
        }

        foreach ($this->unknownTopicKeys as $key) {
            $this->call( $this->topics[$key]['subscriber'], $event);
        }
    }

    private function callv2($callable, $event)
    {
        if( is_array($callable) ) {
            foreach ($callable as $fn ) {
                $this->callv2($fn, $event);
            }
        }else{
            call_user_func($callable, $event);
        }
    }

    private function call($subscriber, $event)
    {
        if( $subscriber instanceof EventSubscriber
            && $subscriber->isSubscribedTo($event) ) {
            $subscriber->handle($event);
        }elseif( $subscriber instanceof EventProvider) {
            $collection = $subscriber->getListenersForEvent($event);
            foreach ($collection as $fn ) {
                $this->call($fn, $event);
            }
        }elseif ( is_callable($subscriber, false)) {
            call_user_func($subscriber, $event);
        }
    }
}
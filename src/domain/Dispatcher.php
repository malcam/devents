<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 08/02/2019
 * Time: 04:32 PM
 */
namespace devent\domain;

use devent\contracts\EventDispatcher;
use devent\contracts\EventProvider;
use devent\contracts\EventSubscriber;

class Dispatcher extends Singleton implements EventDispatcher
{
    public static $instance = null;
    private $tree;
    private $index = 0;
    private $exchangeStrategy;

    protected function __construct($exchangeStrategy = null)
    {
        //TODO: separar los topics de los subscribers
        $this->tree = [];

        if( !isset($exchangeStrategy) ) {
            $exchangeStrategy = new \devent\strategies\FanoutStrategy($this);
        }

        $this->exchangeStrategy = $exchangeStrategy;
    }

    public function get($id)
    { //Add container interface
        return isset($this->tree[$id]) ? $this->tree[$id] : null;
    }

    public function has($id)
    {
        return isset($this->tree[$id]);
    }

    public function subscribe($subscriber, $topic = '*')
    {
        $this->assertThatIsSupportedSubscriber($subscriber);

        $this->tree[$topic][] = ["index" => $this->index++, "subscriber" => $subscriber];

        return $this->index;
    }

    private function assertThatIsSupportedSubscriber($subscriber)
    {
        if (!$subscriber instanceof EventSubscriber &&
            !$subscriber instanceof EventProvider &&
            !is_callable($subscriber)) {
            throw new UnsupportedSubscriberException('subscriber: ' . gettype($subscriber) . ' unsupported');
        }
    }

//    public function subscribeV2($topic, $subscriber = null)
//    {
//        if( null === $subscriber ) {
//            $subscriber = $topic;
//            $this->unknownTopicKeys[] = $topic = $this->index;
//        }
//
//        $fn = $subscriber;
//        $this->index++;
//
//        if( $subscriber instanceof EventSubscriber ) {
//            $fn = [$subscriber, 'handle'];
//        }elseif( $subscriber instanceof  EventProvider ) {
//            $fn = function ($event) use ($subscriber) {
//                $collection = $subscriber->getListenersForEvent($event);
//                $this->callv2($collection, $event);
//            };
//        }
//
//        if ( !is_callable($fn, false)) {
//           throw new \Exception('subscriber no puede ser llamado');
//        }
//
//        $this->topics[$topic] = ["index" => $this->index, "subscriber" => $fn];
//
//        return $this->index;
//    }

    public function unsubscribe($index)
    {
        unset($this->tree[$index]);
    }

    /**
     * (event) Lanza un evento
     * @param \devent\contracts\Event|null $event
     * @return void
     */
    public function dispatch($event)
    {
        $this->exchangeStrategy->send($event, ['tree'=>$this->tree]);
    }

//    private function callv2($callable, $event)
//    {
//        if( is_array($callable) ) {
//            foreach ($callable as $fn ) {
//                $this->callv2($fn, $event);
//            }
//        }else{
//            call_user_func($callable, $event);
//        }
//    }

    public function call($subscriber, $event)
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
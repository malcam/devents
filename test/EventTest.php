<?php
declare(strict_types=1);

namespace devent\test;

use devent\domain\Dispatcher;
use devent\domain\Event;
use devent\domain\UnsupportedSubscriberException;
use devent\test\resources\TestSubscriber;

use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
    /**
     * @testdox Se envia un evento a un EventSubscriber en modo fanout
     */
    public function testDispatchToSubscriber()
    {
        $this->expectOutputString('OK');
        $subscriber = new TestSubscriber();

        $dispatcher = Dispatcher::instance();
        $dispatcher->subscribe($subscriber);
        $dispatcher->dispatch(new Event('OK'));
    }

    /**
     * @testdox Se envia un evento a un Provider en modo fanout
     */
    public function testDispatchToProvider()
    {
        $this->expectOutputString('OK');
        $provider = new \devent\test\resources\TestProvider();

        $dispatcher = Dispatcher::instance();
        $dispatcher->subscribe($provider);
        $dispatcher->dispatch(new Event('OK'));
    }

    /**
     * @testdox Lanzando un evento a un subscriber tipo provider
     */
    public function testDispatchToSubscriberFunction()
    {
        $this->expectOutputString('OK');

        $dispatcher = Dispatcher::instance();
        $dispatcher->subscribe(function (){
            echo 'OK';
        });

        $dispatcher->dispatch(new Event('OK'));
    }

    /**
     * @testdox Se envia un evento a una clase callable
     */
    public function testDispatchToCallableClass()
    {
        $this->expectOutputString('OK');
        $provider = new \devent\test\resources\CallableClass();

        $dispatcher = Dispatcher::instance();
        $dispatcher->subscribe($provider);
        $dispatcher->dispatch(new Event('OK'));
    }

    /**
     * @testdox Se envia un evento a un subscriber no soportado
     */
    public function testDispatchWithUnsupportedSubscriber()
    {
        $this->expectException(UnsupportedSubscriberException::class);
        $provider = [];

        $dispatcher = Dispatcher::instance();
        $dispatcher->subscribe($provider);
        $dispatcher->dispatch(new Event('OK'));
    }
}

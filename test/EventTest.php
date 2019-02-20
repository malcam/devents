<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
    /**
     * @testdox Lanzando un evento
     * @param mixed[] $data
     */
    public function testTriggerEvent($data = null)
    {
        $this->expectOutputString('Algo a pasado');
        //@dataProvider dataProvider
        $event = new \devent\domain\Event('Algo a pasado');
        $subscriber = new \devent\domain\Subscriber();

        $dispatcher = \devent\domain\Dispatcher::instance();
        $dispatcher->subscribe($subscriber);

        $dispatcher->dispatch($event);
    }

    /**
     * @testdox lanzando un evento a un subscriber
     */
    public function testDispatchToEventSubscriber()
    {
        $output = 'Algo a pasado';
        $this->expectOutputString($output);

        $event = new \devent\domain\Event($output);
        $subscriber = new \devent\domain\Subscriber();

        $dispatcher = \devent\domain\Dispatcher::instance();
        $dispatcher->subscribe($subscriber);

        $dispatcher->dispatch($event);
    }

    /**
     * @testdox Lanzando un evento a un subscriber tipo provider
     */
    public function testDispatchToListenerProvider()
    {
        $output = 'Algo a pasado';
        $this->expectOutputString($output);

        $event = new \devent\domain\Event($output);
        $subscriber = new \devent\domain\Provider();

        $dispatcher = \devent\domain\Dispatcher::instance();
        $dispatcher->subscribe($subscriber);

        $dispatcher->dispatch($event);
    }

    /**
     * @testdox Usando dos tipos de subscribers
     */
    public function testDispatchToMultipleSubscribers()
    {
        $output = 'Algo a pasado';
        $this->expectOutputString($output);

        $event = new \devent\domain\Event($output);
        $subscriberOne = new \devent\domain\Provider();
        $subscriberTwo = new \devent\domain\Subscriber();

        $dispatcher = \devent\domain\Dispatcher::instance();
        $dispatcher->subscribe($subscriberOne);
        $dispatcher->subscribe($subscriberTwo);

        $dispatcher->dispatch($event);
    }

    /**
     * @testdox Usando un subscriber no soportado
     */
    public function testDispatchWithUnsupportedSubscriber()
    {
        $this->expectException(InvalidArgumentException::class);

        $output = 'Algo a pasado';
        $event = new \devent\domain\Event($output);

        $dispatcher = \devent\domain\Dispatcher::instance();
        $dispatcher->subscribe(null);

        $dispatcher->dispatch($event);
    }

    public function DataProvider()
    {
        return require __DIR__.'/resources/event_data.php';
    }
}

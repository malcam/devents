<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
    /**
     * @testdox Agregado correcto de Nota de Ingreos de HSP.
     *
     *
     * @param mixed[] $data
     */
    public function testTriggerEvent($data = null)
    {
        //@dataProvider dataProvider
        $event = new \devent\domain\Event('Algo a pasado');
        $subscriber = new \devent\domain\Subscriber();

        $dispatcher = \devent\domain\Dispatcher::instance();
        $dispatcher->subscribe($subscriber);
        $dispatcher->dispatch($event);
    }

    public function DataProvider()
    {
        return require __DIR__.'/resources/event_data.php';
    }
}

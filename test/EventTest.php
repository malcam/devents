<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
    /**
     * @testdox Agregado correcto de Nota de Ingreos de HSP.
     *
     * @dataProvider dataProvider
     * @param mixed[] $data
     */
    public function testTriggerEvent($data)
    {
        $event = new \devent\domain\event('Algo a pasado');
        $dispatcher = \devent\domain\Dispatcher::instance()->dispatch($event);
    }

    public function DataProvider()
    {
        return require __DIR__.'/resources/event_data.php';
    }
}

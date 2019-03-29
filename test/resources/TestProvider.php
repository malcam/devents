<?php
/**
 * Created by PhpStorm.
 * User: asaelel
 * Date: 28/03/2019
 * Time: 10:38 PM
 */
namespace devent\test\resources;

use devent\domain\Provider;

class TestProvider extends Provider
{

    public function handle(object $event): void
    {
        echo $event->body();
    }
}
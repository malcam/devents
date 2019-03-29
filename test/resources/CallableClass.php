<?php
/**
 * Created by PhpStorm.
 * User: asaelel
 * Date: 28/03/2019
 * Time: 11:01 PM
 */

namespace devent\test\resources;


class CallableClass
{
    public function __invoke() {
        echo "OK";
    }
}
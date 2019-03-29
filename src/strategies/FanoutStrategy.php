<?php
/**
 * Created by PhpStorm.
 * User: asaelel
 * Date: 28/03/2019
 * Time: 08:36 PM
 */

namespace devent\strategies;


class FanoutStrategy
{
    private $runner;

    public function __construct($runner)
    {
        $this->runner = $runner;
    }

    public function send($event, $extras)
    {
        $tree  = $extras['tree'];

        foreach ($tree as $path => $entry) {
            foreach ($entry as $container) {
                $this->runner->call($container['subscriber'], $event);
            }
        }
    }
}
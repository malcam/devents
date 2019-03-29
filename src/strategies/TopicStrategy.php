<?php
/**
 * Created by PhpStorm.
 * User: asaelel
 * Date: 28/03/2019
 * Time: 08:50 PM
 */

namespace devent\strategies;


class TopicStrategy
{
    private $runner;

    public function __construct($runner)
    {
        $this->runner = $runner;
    }

    public function send($event, $extras)
    {
        $tree  = $extras['tree'];
        $topic = $event::TOPIC;

        $prefix = $topic;

        foreach ($tree as $path => $entry) {
            while (false !== $pos = strrpos($prefix, '.')) {

                $prefix = substr($topic, 0, $pos);

                if( $prefix == $path ) {
                    foreach ($entry as $container) {
                        $this->runner->call($container['subscriber'], $event);
                    }
                    break;
                }
            }
        }
    }
}
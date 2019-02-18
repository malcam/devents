<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 08/02/2019
 * Time: 04:37 PM
 * https://stackoverflow.com/questions/24852125/what-is-singleton-in-php
 */
namespace devent\domain\contracts;

abstract class Singleton
{
    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar Singleton $instance The *Singleton* instances of this class.
     *
     * @return static The *Singleton* instance.
     */
    public static function instance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
        throw new \BadMethodCallException('Clone is not supported');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Malcam
 * Date: 13/02/2019
 * Time: 03:33 PM
 */

namespace devent\domain;

use \devent\domain\contracts\Event as EventInterface;

class Event implements EventInterface
{
    private $body;
    private $createdAt;

    public function __construct($body, $createdAt = null)
    {
        $this->body = $body;
        $this->createdAt = $createdAt ?? gmdate(\DateTime::ATOM);
    }

    /**
     * Crea un objeto Event
     * @param $data con los valores del constructor
     * @return Event
     */
    public static function create($data)
    {
        return new self(
            $data['body'],
            $data['createdAt']
        );
    }

    /**
     * Retorna la fecha de creacion del evento
     * @return \DateTime|false|string|null
     */
    public function createdAt()
    {
        return $this->createdAt;
    }

    /**
     * Retorna el body del evento
     * @return mixed
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * Crea un nuevo objeto event ccn el body especificado
     * @param $body
     * @return Event
     */
    public function withBody($body)
    {
        return self::create(['body'=>$body]);
    }
}
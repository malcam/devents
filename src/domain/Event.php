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
        //gmdate(\DateTime::ISO8601);
        $this->body = $body;
        $this->createdAt = $createdAt ?? gmdate(\DateTime::ATOM);
    }

    public static function create($data)
    {
        return new self(
            $data['body'],
            $data['createdAt']
        );
    }

    public function createdAt()
    {
        // TODO: Implement createdAt() method.
    }

    public function getBody()
    {
        // TODO: Implement getBody() method.
    }

    public function withBody($body)
    {
        // TODO: Implement withBody() method.
    }
}
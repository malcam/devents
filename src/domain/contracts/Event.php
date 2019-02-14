<?php
namespace devent\domain\contracts;

interface Event
{
    /**
     * @return \DateTime
     */
    public function createdAt();

    public function getBody();

    public function withBody($body);
}
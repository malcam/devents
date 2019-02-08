<?php
namespace devent\domain\contracts;

interface Event
{
    /**
     * @return \DateTime
     */
    public function createdAt();
}
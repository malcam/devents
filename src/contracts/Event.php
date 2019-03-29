<?php
namespace devent\contracts;

interface Event
{
    /**
     * @return \DateTime
     */
    public function createdAt();

    public function body();

    public function withBody($body);
}
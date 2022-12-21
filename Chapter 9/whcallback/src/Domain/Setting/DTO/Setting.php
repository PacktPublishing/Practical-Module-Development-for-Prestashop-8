<?php

namespace WebHelpers\WHCallback\Domain\Setting\DTO;

class Setting
{
    private $hours;

    public function __construct($hours)
    {
        $this->hours = $hours;
    }

    public function getHours()
    {
        return $this->hours;
    }
}

<?php

namespace WebHelpers\WHCallback\Domain\Setting\Command;

class EditSettingCommand
{
    private $hours;

    public function __construct(int $hours)
    {
        $this->hours = $hours;
    }

    public function getHours()
    {
        return $this->hours;
    }
}

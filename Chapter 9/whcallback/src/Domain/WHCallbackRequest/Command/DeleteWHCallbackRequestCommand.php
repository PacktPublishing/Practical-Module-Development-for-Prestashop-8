<?php

namespace WebHelpers\WHCallback\Domain\WHCallbackRequest\Command;

class DeleteWHCallbackRequestCommand
{
    private $idRequest;

    public function __construct(int $idRequest)
    {
        $this->idRequest = $idRequest;
    }

    public function getIdRequest()
    {
        return $this->idRequest;
    }
}

<?php

namespace WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command;

class DeleteExtensionCommand
{
    private $idCategory;

    public function __construct(int $idCategory)
    {
        $this->idCategory = $idCategory;
    }

    public function getIdCategory()
    {
        return $this->idCategory;
    }
}

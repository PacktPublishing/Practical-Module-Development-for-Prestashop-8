<?php

namespace WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command;

class EditExtensionCommand
{
    private $idExtension;
    private $idCategory;
    private $shortText;
    private $longText;
    private $filename;

    public function __construct($idExtension, $idCategory, $shortText, $longText, $filename)
    {
        $this->idExtension = $idExtension;
        $this->idCategory = $idCategory;
        $this->shortText = $shortText;
        $this->longText = $longText;
        $this->filename = $filename;
    }

    public function getIdExtension()
    {
        return $this->idExtension;
    }

    public function getIdCategory()
    {
        return $this->idCategory;
    }

    public function getShortText()
    {
        return $this->shortText;
    }

    public function getLongText()
    {
        return $this->longText;
    }

    public function getFilename()
    {
        return $this->filename;
    }
}

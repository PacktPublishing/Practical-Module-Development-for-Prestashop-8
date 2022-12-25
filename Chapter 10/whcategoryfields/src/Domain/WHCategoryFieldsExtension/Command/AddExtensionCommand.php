<?php

namespace WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command;

class AddExtensionCommand
{
    private $idCategory;
    private $shortText;
    private $longText;
    private $filename;

    public function __construct($idCategory, $shortText, $longText, $filename)
    {
        $this->idCategory = $idCategory;
        $this->shortText = $shortText;
        $this->longText = $longText;
        $this->filename = $filename;
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

<?php

namespace WebHelpers\WHBlog\Domain\WHBlogCategory\Command;

class EditCategoryCommand
{
    private $title;
    private $idCategory;

    public function __construct($idCategory, $title)
    {
        $this->idCategory = $idCategory;
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getIdCategory()
    {
        return $this->idCategory;
    }
}

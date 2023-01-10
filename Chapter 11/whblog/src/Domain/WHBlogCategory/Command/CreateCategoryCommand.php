<?php

namespace WebHelpers\WHBlog\Domain\WHBlogCategory\Command;

class CreateCategoryCommand
{
    private $title;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
}

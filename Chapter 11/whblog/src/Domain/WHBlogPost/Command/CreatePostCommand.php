<?php

namespace WebHelpers\WHBlog\Domain\WHBlogPost\Command;

class CreatePostCommand
{
    private $title;
    private $content;
    private $imageFile;
    private $categories;

    public function __construct($title, $content, $imageFile, $categories)
    {
        $this->title = $title;
        $this->content = $content;
        $this->imageFile = $imageFile;
        $this->categories = $categories;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getCategories()
    {
        return $this->categories;
    }
}

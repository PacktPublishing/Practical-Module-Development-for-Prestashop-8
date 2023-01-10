<?php

namespace WebHelpers\WHBlog\Domain\WHBlogPost\Command;

class EditPostCommand
{
    private $idPost;
    private $title;
    private $content;
    private $imageFile;
    private $categories;

    public function __construct($idPost, $title, $content, $imageFile, $categories)
    {
        $this->idPost = $idPost;
        $this->title = $title;
        $this->content = $content;
        $this->imageFile = $imageFile;
        $this->categories = $categories;
    }

    public function getIdPost()
    {
        return $this->idPost;
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

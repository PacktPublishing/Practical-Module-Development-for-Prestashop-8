<?php

namespace WebHelpers\WHBlog\Domain\WHBlogPost\QueryHandler;

use WebHelpers\WHBlog\Domain\WHBlogPost\DTO\WHBlogPost;
use WebHelpers\WHBlog\Domain\WHBlogPost\Query\GetPostForEditing;

class GetPostForEditingHandler
{
    private $postRepository;

    public function __construct($postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(GetPostForEditing $getPostForEditing)
    {
        $blogPost = $this->postRepository->findOneBy(['id' => $getPostForEditing->getIdPost()]);
        if(!is_null($blogPost)){
            return new WHBlogPost($blogPost->getTitleLangs(), $blogPost->getContentLangs(), $blogPost->getFilename(), $blogPost->getCategories()->toArray());
        }else{

        }

    }
}

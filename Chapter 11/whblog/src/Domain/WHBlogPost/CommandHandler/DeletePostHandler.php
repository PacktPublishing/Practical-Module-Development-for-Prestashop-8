<?php

namespace WebHelpers\WHBlog\Domain\WHBlogPost\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use WebHelpers\WHBlog\Domain\WHBlogPost\Command\DeletePostCommand;
use WebHelpers\WHBlog\Domain\WHBlogPost\Exception\CannotDeletePostException;
use WebHelpers\WHBlog\Entity\WHBlogPost;

class DeletePostHandler
{
    private $entityManager;
    private $postRepository;

    public function __construct($entityManager, $postRepository)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
    }

    public function handle(DeletePostCommand $deletePostCommand)
    {
        try{
            $blogPost = $this->postRepository->findOneBy(['id' => $deletePostCommand->getIdPost()]);
            if(!is_null($blogPost)){
                if($blogPost->getFilename()!="" && file_exists(_PS_MODULE_DIR_."whblog/views/img/uploads/".$blogPost->getFilename())){
                    unlink(_PS_MODULE_DIR_."whblog/views/img/uploads/".$blogPost->getFilename());
                }
                $this->entityManager->remove($blogPost);
                $this->entityManager->flush();
            }
        }catch(\Exception $e){
            throw new CannotDeletePostException(
                sprintf('Failed to delete the blog post')
            );
        }
    }
}

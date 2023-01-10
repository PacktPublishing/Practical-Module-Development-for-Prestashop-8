<?php

namespace WebHelpers\WHBlog\Domain\WHBlogPost\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;

use WebHelpers\WHBlog\Entity\WHBlogCategoryPost;
use WebHelpers\WHBlog\Entity\WHBlogPost;
use WebHelpers\WHBlog\Domain\WHBlogPost\Command\CreatePostCommand;
use WebHelpers\WHBlog\Domain\WHBlogPost\Exception\CannotCreatePostException;
use WebHelpers\WHBlog\Entity\WHBlogPostLang;

class CreatePostHandler
{
    private $entityManager;
    private $postRepository;
    private $categoryRepository;
    private $categoryPostRepository;
    private $langRepository;

    public function __construct($entityManager, $postRepository, $categoryRepository, $categoryPostRepository, $langRepository)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->categoryPostRepository = $categoryPostRepository;
        $this->langRepository = $langRepository;
    }

    public function handle(CreatePostCommand $createPostCommand)
    {
        try{
            $title = $createPostCommand->getTitle();
            $content = $createPostCommand->getContent();
            $imageFile = $createPostCommand->getImageFile();
            $languages = $this->langRepository->findAll();
            $categories = $createPostCommand->getCategories();

            $blogPost = new WHBlogPost();
            foreach($languages as $language)
            {
                $blogPostLang = new WHBlogPostLang();
                $blogPostLang->setLang($language);
                $blogPostLang->setTitle($title[$language->getId()]);
                $blogPostLang->setContent($content[$language->getId()]);
                $blogPost->addPostLang($blogPostLang);
            }

            $filename = "";
            if(!is_null($imageFile)){
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        _PS_MODULE_DIR_."whblog/views/img/uploads/",
                        $filename
                    );
                } catch (FileException $e) {
                }
            }

            $blogPost->setFilename($filename);
            if(!is_null($categories)){
                foreach($categories as $categoryId)
                {
                    $category = $this->categoryRepository->findOneBy(['id'=>$categoryId]);
                    $relation = new WHBlogCategoryPost();
                    $relation->setCategory($category);
                    $relation->setPost($blogPost);
                    $this->entityManager->persist($relation);
                }
            }
            $this->entityManager->persist($blogPost);
            $this->entityManager->flush();
        }catch(\Exception $e){
            throw new CannotCreatePostException(
                sprintf('Failed to create the blog post')
            );
        }
    }
}

<?php

namespace WebHelpers\WHBlog\Domain\WHBlogPost\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use WebHelpers\WHBlog\Domain\WHBlogPost\Command\EditPostCommand;
use WebHelpers\WHBlog\Domain\WHBlogPost\Exception\CannotEditPostException;
use WebHelpers\WHBlog\Entity\WHBlogPost;
use WebHelpers\WHBlog\Entity\WHBlogPostLang;
use WebHelpers\WHBlog\Entity\WHBlogCategoryPost;

class EditPostHandler
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

    public function handle(EditPostCommand $editPostCommand)
    {
        try{
            $idPost = $editPostCommand->getIdPost();
            $title = $editPostCommand->getTitle();
            $content = $editPostCommand->getContent();
            $imageFile = $editPostCommand->getImageFile();
            $languages = $this->langRepository->findAll();
            $categories = $editPostCommand->getCategories();

            $categoryPostRelationShips = $this->categoryPostRepository->findBy(['post'=>$idPost]);
            foreach($categoryPostRelationShips as $relation)
            {
                $this->entityManager->remove($relation);
            }
            $this->entityManager->flush();

            $blogPost = $this->postRepository->findOneBy(['id' => $idPost]);
            foreach($languages as $language)
            {
                $blogPostLang = $blogPost->getPostLangByLangId($language->getId());
                $needsAdd = false;
                if(is_null($blogPostLang)){
                    $blogPostLang = new WHBlogPostLang();
                    $needsAdd = true;
                }
                $blogPostLang->setLang($language);
                $blogPostLang->setTitle($title[$language->getId()]);
                $blogPostLang->setContent($content[$language->getId()]);
                if($needsAdd){
                    $blogPost->addPostLang($blogPostLang);
                }
            }
            $filename = "";
            if(!is_null($imageFile)){
                if($blogPost->getFilename()!="" && file_exists(_PS_MODULE_DIR_."whblog/views/img/uploads/".$blogPost->getFilename())){
                    unlink(_PS_MODULE_DIR_."whblog/views/img/uploads/".$blogPost->getFilename());
                }
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        _PS_MODULE_DIR_."whblog/views/img/uploads/",
                        $filename
                    );
                } catch (FileException $e) {
                }
                $blogPost->setFilename($filename);
            }

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
            throw new CannotEditPostException(
                sprintf('Failed to edit the blog post')
            );
        }
    }
}

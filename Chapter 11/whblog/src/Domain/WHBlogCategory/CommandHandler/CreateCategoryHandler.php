<?php

namespace WebHelpers\WHBlog\Domain\WHBlogCategory\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;

use WebHelpers\WHBlog\Entity\WHBlogCategory;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Command\CreateCategoryCommand;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Exception\CannotAddCategoryException;
use WebHelpers\WHBlog\Entity\WHBlogCategoryLang;

class CreateCategoryHandler
{
    private $entityManager;
    private $categoryRepository;
    private $langRepository;

    public function __construct($entityManager, $categoryRepository, $langRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
        $this->langRepository = $langRepository;
    }

    public function handle(CreateCategoryCommand $createCategoryCommand)
    {
        try{
            $title = $createCategoryCommand->getTitle();
            $languages = $this->langRepository->findAll();

            $blogCategory = new WHBlogCategory();
            foreach($languages as $language)
            {
                $blogCategoryLang = new WHBlogCategoryLang();
                $blogCategoryLang->setLang($language);
                $blogCategoryLang->setTitle($title[$language->getId()]);
                $blogCategory->addCategoryLang($blogCategoryLang);
            }
            $this->entityManager->persist($blogCategory);
            $this->entityManager->flush();
        }catch(\Exception $e){
            throw new CannotCreateCategoryException(
                sprintf('Failed to create the blog category')
            );
        }
    }
}

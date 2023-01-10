<?php

namespace WebHelpers\WHBlog\Domain\WHBlogCategory\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;

use WebHelpers\WHBlog\Entity\WHBlogCategory;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Command\EditCategoryCommand;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Exception\CannotEditCategoryException;
use WebHelpers\WHBlog\Entity\WHBlogCategoryLang;

class EditCategoryHandler
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

    public function handle(EditCategoryCommand $editCategoryCommand)
    {
        try{
            $title = $editCategoryCommand->getTitle();
            $languages = $this->langRepository->findAll();

            $blogCategory = $this->categoryRepository->findOneBy(['id' => $editCategoryCommand->getIdCategory()]);
            foreach($languages as $language)
            {
                $blogCategoryLang = $blogCategory->getCategoryLangByLangId($language->getId());
                $needsAdd = false;
                if(is_null($blogCategoryLang)){
                    $blogCategoryLang = new WHBlogCategoryLang();
                    $needsAdd = true;
                }
                $blogCategoryLang->setLang($language);
                $blogCategoryLang->setTitle($title[$language->getId()]);
                if($needsAdd){
                    $blogCategory->addCategoryLang($blogCategoryLang);
                }
            }
            $this->entityManager->persist($blogCategory);
            $this->entityManager->flush();
        }catch(\Exception $e){
            throw new CannotEditCategoryException(
                sprintf('Failed to edit the blog category')
            );
        }
    }
}

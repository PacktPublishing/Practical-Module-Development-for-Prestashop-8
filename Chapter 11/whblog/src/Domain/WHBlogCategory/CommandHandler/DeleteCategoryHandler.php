<?php

namespace WebHelpers\WHBlog\Domain\WHBlogCategory\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;

use WebHelpers\WHBlog\Entity\WHBlogCategory;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Command\DeleteCategoryCommand;
use WebHelpers\WHBlog\Domain\WHBlogCategory\Exception\CannotDeleteCategoryException;

class DeleteCategoryHandler
{
    private $entityManager;
    private $categoryRepository;

    public function __construct($entityManager, $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(DeleteCategoryCommand $deleteCategoryCommand)
    {
        try{
            $blogCategory = $this->categoryRepository->findOneBy(['id' => $deleteCategoryCommand->getIdCategory()]);
            if(!is_null($blogCategory)){
                $this->entityManager->remove($blogCategory);
                $this->entityManager->flush();
            }
        }catch(\Exception $e){
            throw new CannotDeleteCategoryException(
                sprintf('Failed to delete the blog category')
            );
        }
    }
}

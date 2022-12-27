<?php

namespace WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command\DeleteExtensionCommand;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Exception\CannotDeleteExtensionException;
use WebHelpers\WHCategoryFields\Entity\WHCategoryFieldsExtension;

class DeleteExtensionHandler
{
    private $entityManager;
    private $extensionRepository;

    public function __construct($entityManager, $extensionRepository)
    {
        $this->entityManager = $entityManager;
        $this->extensionRepository = $extensionRepository;
    }

    public function handle(DeleteExtensionCommand $deleteExtensionCommand)
    {
        try{
            $extension = $this->extensionRepository->findOneBy(['idCategory' => $deleteExtensionCommand->getIdCategory()]);
            if(!is_null($extension))
            {
                $this->entityManager->remove($extension);
                $this->entityManager->flush();
            }
        }catch(\Exception $e){
            throw new CannotDeleteExtensionException(
                sprintf('Failed to delete the extension')
            );
        }
    }
}

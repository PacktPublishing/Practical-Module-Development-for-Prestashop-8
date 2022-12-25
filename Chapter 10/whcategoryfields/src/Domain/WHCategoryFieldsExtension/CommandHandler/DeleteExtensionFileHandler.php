<?php

namespace WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command\DeleteExtensionFileCommand;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Exception\CannotDeleteExtensionFileException;

class DeleteExtensionFileHandler
{
    private $entityManager;
    private $extensionRepository;

    public function __construct(EntityManagerInterface $entityManager, $extensionRepository)
    {
        $this->entityManager = $entityManager;
        $this->extensionRepository = $extensionRepository;
    }

    public function handle(DeleteExtensionFileCommand $deleteExtensionFileCommand)
    {
        try{
           $extension = $this->extensionRepository->findOneBy(['idCategory' => $deleteExtensionFileCommand->getIdCategory()]);
           if(!is_null($extension) && $extension->getFilename()!=""){
               unlink(_PS_MODULE_DIR_."whcategoryfields/views/img/uploads/".$extension->getFilename());
               $extension->setFilename("");
               $this->entityManager->flush();
           }
        }catch(\Exception $e){
            throw new CannotDeleteExtensionFileException(
                sprintf('Failed to delete the extension file')
            );
        }
    }
}

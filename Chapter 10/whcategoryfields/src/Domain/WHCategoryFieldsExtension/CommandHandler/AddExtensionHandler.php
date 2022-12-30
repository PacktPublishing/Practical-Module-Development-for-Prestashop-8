<?php

namespace WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command\AddExtensionCommand;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Exception\CannotAddExtensionException;
use WebHelpers\WHCategoryFields\Entity\WHCategoryFieldsExtension;
use WebHelpers\WHCategoryFields\Entity\WHCategoryFieldsExtensionLang;

class AddExtensionHandler
{
    private $entityManager;
    private $extensionRepository;
    private $langRepository;

    public function __construct($entityManager, $extensionRepository, $langRepository)
    {
        $this->entityManager = $entityManager;
        $this->extensionRepository = $extensionRepository;
        $this->langRepository = $langRepository;
    }

    public function handle(AddExtensionCommand $addExtensionCommand)
    {
        try{
            $shortText = $addExtensionCommand->getShortText();
            $longText = $addExtensionCommand->getLongText();
            $languages = $this->langRepository->findAll();

            $extension = new WHCategoryFieldsExtension();
            foreach($languages as $language)
            {
                $extensionLang = new WHCategoryFieldsExtensionLang();
                $extensionLang->setLang($language);
                $extensionLang->setShortText($shortText[$language->getId()]);
                $extensionLang->setLongText($longText[$language->getId()]);
                $extension->addExtensionLang($extensionLang);
            }
            $extension->setExtensionDateAdd();
            $extension->setIdCategory($addExtensionCommand->getIdCategory());
            $extension->setFilename($addExtensionCommand->getFilename());
            $this->entityManager->persist($extension);
            $this->entityManager->flush();
        }catch(\Exception $e){
            throw new CannotAddExtensionException(
                sprintf('Failed to add the extension')
            );
        }
    }
}

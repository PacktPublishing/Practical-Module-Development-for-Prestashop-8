<?php

namespace WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Command\EditExtensionCommand;
use WebHelpers\WHCategoryFields\Domain\WHCategoryFieldsExtension\Exception\CannotEditExtensionException;
use WebHelpers\WHCategoryFields\Entity\WHCategoryFieldsExtension;

class EditExtensionHandler
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

    public function handle(EditExtensionCommand $editExtensionCommand)
    {
        try{
            $shortText = $editExtensionCommand->getShortText();
            $longText = $editExtensionCommand->getLongText();
            $languages = $this->langRepository->findAll();

            $extension = $this->extensionRepository->findOneBy(['id' => $editExtensionCommand->getIdExtension()]);
            foreach($languages as $language)
            {
                $extensionLang = $extension->getExtensionLangByLangId($language->getId());
                $needsAdd = false;
                if(is_null($extensionLang)){
                    $extensionLang = new WHCategoryFieldsExtensionLang();
                    $needsAdd = true;
                }
                $extensionLang->setLang($language);
                $extensionLang->setShortText($shortText[$language->getId()]);
                $extensionLang->setLongText($longText[$language->getId()]);
                if($needsAdd){
                    $extension->addExtensionLang($extensionLang);
                }

            }
            $extension->setIdCategory($editExtensionCommand->getIdCategory());
            $extension->setFilename($editExtensionCommand->getFilename());
            $this->entityManager->flush();
        }catch(\Exception $e){
            throw new CannotEditExtensionException(
                sprintf('Failed to edit the extension')
            );
        }
    }
}

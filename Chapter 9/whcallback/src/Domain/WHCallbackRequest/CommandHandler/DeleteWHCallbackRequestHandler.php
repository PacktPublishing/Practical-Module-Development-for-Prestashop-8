<?php

namespace WebHelpers\WHCallback\Domain\WHCallbackRequest\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use WebHelpers\WHCallback\Domain\WHCallbackRequest\Command\DeleteWHCallbackRequestCommand;
use WebHelpers\WHCallback\Domain\WHCallbackRequest\Exception\CannotDeleteRequestException;

class DeleteWHCallbackRequestHandler
{
    private $entityManager;
    private $whCallbackRepository;

    public function __construct(EntityManagerInterface $entityManager, $whCallbackRepository)
    {
        $this->entityManager = $entityManager;
        $this->whCallbackRepository = $whCallbackRepository;
    }

    public function handle(DeleteWHCallbackRequestCommand $deleteWHCallbackRequestCommand)
    {
        try{
           $callbackRequest = $this->whCallbackRepository->findOneById($deleteWHCallbackRequestCommand->getIdRequest());
           $this->entityManager->remove($callbackRequest);
           $this->entityManager->flush();
        }catch(\Exception $e){
            throw new CannotDeleteRequestException(
                sprintf('Failed to delete the request with id "%s".', $deleteWHCallbackRequestCommand->getIdRequest())
            );
        }
    }
}

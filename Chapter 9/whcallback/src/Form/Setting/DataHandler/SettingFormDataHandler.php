<?php
namespace WebHelpers\WHCallback\Form\Setting\DataHandler;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use WebHelpers\WHCallback\Domain\Setting\Command\EditSettingCommand;

final class SettingFormDataHandler implements FormDataHandlerInterface
{
    private $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function create(array $data)
    {
        $this->update(0, $data);
    }

    public function update($id, array $data)
    {
        $editSettingCommand = new EditSettingCommand($data['hours']);
        $this->commandBus->handle($editSettingCommand);
    }
}

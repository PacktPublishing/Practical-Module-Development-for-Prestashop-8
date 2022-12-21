<?php
namespace WebHelpers\WHCallback\Form\Setting\DataProvider;

use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;
use WebHelpers\WHCallback\Domain\Setting\Query\GetSettingForEditing;
use WebHelpers\WHCallback\Domain\Setting\DTO\Setting;

final class SettingFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var CommandBusInterface
     */
    private $queryBus;
    private $setting;

    /**
     * @param CommandBusInterface $queryBus
     */
    public function __construct(CommandBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
        $this->setting = $this->queryBus->handle(new GetSettingForEditing());
    }

    /**
     * Get form data for given object with given id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getData($id=0)
    {
        return [
            'hours' => $this->setting->getHours(),
        ];
    }

    /**
     * Get default form data.
     *
     * @return mixed
     */
    public function getDefaultData()
    {
        return [
            'hours' => $this->setting->getHours(),
        ];
    }
}

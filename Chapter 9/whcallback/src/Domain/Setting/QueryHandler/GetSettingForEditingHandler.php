<?php

namespace WebHelpers\WHCallback\Domain\Setting\QueryHandler;

use WebHelpers\WHCallback\Domain\Setting\DTO\Setting;
use WebHelpers\WHCallback\Domain\Setting\Query\GetSettingForEditing;

class GetSettingForEditingHandler
{
    private $configuration;
    private $configurationKey;

    public function __construct($configuration, $configurationKey)
    {
        $this->configuration = $configuration;
        $this->configurationKey = $configurationKey;
    }

    public function handle(GetSettingForEditing $getSettingForEditing)
    {
        $hours = $this->configuration->get($this->configurationKey, 0);
        return new Setting($hours);
    }
}

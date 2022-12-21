<?php

namespace WebHelpers\WHCallback\Domain\Setting\CommandHandler;

use WebHelpers\WHCallback\Domain\Setting\Command\EditSettingCommand;

class EditSettingHandler
{
    private $configuration;
    private $configurationKey;

    public function __construct($configuration, $configurationKey)
    {
        $this->configuration = $configuration;
        $this->configurationKey = $configurationKey;
    }

    public function handle(EditSettingCommand $editSettingCommand)
    {
        $this->configuration->set($this->configurationKey, $editSettingCommand->getHours());
    }
}

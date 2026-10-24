<?php

namespace App\Logging;

use Illuminate\Log\LogManager;

class PrivacyLogManager extends LogManager
{
    protected function createEmergencyLogger()
    {
        $logger = parent::createEmergencyLogger();
        $logger->getLogger()->pushProcessor(new PrivacyProcessor);

        return $logger;
    }
}

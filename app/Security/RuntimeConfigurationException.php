<?php

namespace App\Security;

use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class RuntimeConfigurationException extends ServiceUnavailableHttpException
{
    public function __construct(public readonly array $fields)
    {
        parent::__construct(60, 'Required application configuration is missing or invalid. Run the server-side security:check command.');
    }
}

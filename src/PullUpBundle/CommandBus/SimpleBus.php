<?php

namespace PullUpBundle\CommandBus;

use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware as CommandBus;

/**
 * Class SimpleBus
 * @package UmowieniBundle\CommandBus
 */
class SimpleBus
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * SimpleBus constructor.
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }
    /**
     * @param $command
     */
    public function handle($command)
    {
        $this->commandBus->handle($command);
    }
}

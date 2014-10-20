<?php
/*
 * This file is part of the Evolution7BugsnagBundle.
 *
 * (c) Evolution 7 <http://www.evolution7.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Evolution7\BugsnagBundle\EventListener\Exception;

use Evolution7\BugsnagBundle\Bugsnag\ClientLoader;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;

/**
 * The BugsnagBundle ConsoleListener.
 *
 * Handles exceptions that occur in console commands.
 *
 */
class ConsoleListener
{
    protected $client;

    /**
     * Constructor
     *
     * @param \Evolution7\BugsnagBundle\Bugsnag\ClientLoader $client
     */
    public function __construct(ClientLoader $client)
    {
        $this->client = $client;
    }

    /**
     * Method for handling the actual exceptions
     *
     * @param  \Symfony\Component\Console\Event\ConsoleExceptionEvent $event [description]
     */
    public function onConsoleException(ConsoleExceptionEvent $event)
    {
        $command = $event->getCommand();
        $exception = $event->getException();
        $this->client->notifyOnException($exception);
        error_log(
            sprintf(
                "command:%s has thrown %s in: %s:%d",
                $command->getName(),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            )
        );
    }
}

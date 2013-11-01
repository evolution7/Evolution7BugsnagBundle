<?php
/*
 * This file is part of the Evolution7BugsnagBundle.
 *
 * (c) Evolution 7 <http://www.evolution7.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Evolution7\BugsnagBundle\EventListener;

use Evolution7\BugsnagBundle\Bugsnag\ClientLoader,
    Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent,
    Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * The BugsnagBundle ExceptionListener.
 *
 * Handles exceptions that occur in the code base.
 *
 */
class ExceptionListener
{
    protected $client;

    /**
     * Constructor
     *
     * @param Evolution7\BugsnagBundle\Bugsnag\ClientLoader $client
     */
    public function __construct(ClientLoader $client)
    {
        $this->client = $client;
    }

    /**
     * Method for handling the actual exceptions
     *
     * @param  Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event [description]
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpException) {
            return;
        }

        $this->client->notifyOnException($exception);
        error_log($exception->getMessage().' in: '.$exception->getFile().':'.$exception->getLine());
    }
}

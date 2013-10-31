<?php
namespace Evolution7\BugsnagBundle\EventListener;

use Evolution7\BugsnagBundle\Bugsnag\ClientLoader,
    Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * The BugsnagBundle ShutdownListener.
 *
 * Handles shutdown errors and make sure they get logged.
 *
 * @license     http://www.opensource.org/licenses/mit-license.php
 */
class ShutdownListener
{
    protected $client;

    public function __construct(ClientLoader $client)
    {
        $this->client = $client;
    }

    /**
     * Register the handler on the request.
     *
     * @param Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function register(FilterControllerEvent $event)
    {
        register_shutdown_function(array($this, 'onShutdown'));
    }

    /**
     * Handles the PHP shutdown event.
     *
     * This event exists almost solely to provide a means to catch and log errors that might have been
     * otherwise lost when PHP decided to die unexpectedly.
     */
    public function onShutdown()
    {
        // Get the last error if there was one, if not, let's get out of here.
        if (!$error = error_get_last()) {
            return;
        }

        $fatal  = array(E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR,E_USER_ERROR,E_RECOVERABLE_ERROR);
        if (!in_array($error['type'], $fatal)) {
            return;
        }

        $message   = '[Shutdown Error]: %s';
        $message   = sprintf($message, $error['message']);
        $backtrace = array(array('file' => $error['file'], 'line' => $error['line']));

        $this->client->notifyOnError($message, $backtrace);
        error_log($message.' in: '.$error['file'].':'.$error['line']);
    }
}

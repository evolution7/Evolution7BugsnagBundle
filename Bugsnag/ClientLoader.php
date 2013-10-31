<?php
namespace Evolution7\BugsnagBundle\Bugsnag;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Evolution7\BugsnagBundle\ReleaseStage\ReleaseStageInterface;

/**
 * The BugsnagBundle Client Loader.
 *
 * This class assists in the loading of the bugsnag Client class.
 *
 * @license     http://www.opensource.org/licenses/mit-license.php
 */
class ClientLoader
{
    protected $enabled = false;
    private $bugsnagClient;

    /**
     * Constructor to set up and configure the Bugsnag_Client
     *
     * @param string                $apiKey
     * @param ReleaseStageInterface $releaseStageClass
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(\Bugsnag_Client $bugsnagClient, ReleaseStageInterface $releaseStageClass, ContainerInterface $container)
    {
        $this->bugsnagClient = $bugsnagClient;

        // If we are in the production mode or dev_enabled is true we will sent messages
        if ($container->getParameter('bugsnag.report_in_dev') || $container->getParameter('kernel.environment') == 'prod') {
            $this->enabled = true;
        }

        $request = $container->get('request');

        // Set up the Bugsnag client
        $this->bugsnagClient->setReleaseStage($releaseStageClass->get());
        $this->bugsnagClient->setNotifyReleaseStages($container->getParameter('bugsnag.notify_stages'));
        $this->bugsnagClient->setProjectRoot(realpath($container->getParameter('kernel.root_dir').'/..'));

        // If the proxy settings are configured, provide these to the Bugsnag client
        if ($container->hasParameter('bugsnag.proxy')) {
            $this->bugsnagClient->setProxySettings($container->getParameter('bugsnag.proxy'));
        }

        // Set up result array
        $metaData = array(
            'Symfony' => array()
        );

        // Get and add controller information, if available
        $controller = $request->attributes->get('_controller');
        if ($controller !== null) {
            $metaData['Symfony'] = array('Controller' => $controller);
        }
        $metaData['Symfony']['Route'] = $request->get('_route');
        $this->bugsnagClient->setMetaData($metaData);
    }

    /**
     * Deal with Exceptions
     *
     * @param  \Exception $exception [description]
     */
    public function notifyOnException(\Exception $exception)
    {
        if ($this->enabled) {
            $this->bugsnagClient->notifyException($exception);
        }
    }

    /**
     * Deal with errors
     *
     * @param  string $message   Error message
     * @param  array  $metadata  Metadata to be provided
     */
    public function notifyOnError($message, Array $metadata = null)
    {
        if ($this->enabled) {
            $this->bugsnagClient->notifyError('Error', $message, $metadata);
        }
    }
}

<?php
/*
 * This file is part of the Evolution7BugsnagBundle.
 *
 * (c) Evolution 7 <http://www.evolution7.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Evolution7\BugsnagBundle\Bugsnag;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Evolution7\BugsnagBundle\ReleaseStage\ReleaseStageInterface;

/**
 * The BugsnagBundle Client Loader.
 *
 * This class assists in the loading of the bugsnag Client class.
 *
 */
class ClientLoader
{
    protected $enabled = false;
    private $bugsnagClient;

    /**
     * Constructor to set up and configure the Bugsnag_Client
     *
     * @param \Bugsnag_Client                                          $bugsnagClient
     * @param ReleaseStageInterface                                    $releaseStageClass
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(\Bugsnag_Client $bugsnagClient, ReleaseStageInterface $releaseStageClass, ContainerInterface $container)
    {
        $this->bugsnagClient = $bugsnagClient;

        // If we are in the production mode or dev_enabled is true we will sent messages
        if ($container->getParameter('bugsnag.report_in_dev') || $container->getParameter('kernel.environment') == 'prod') {
            $this->enabled = true;
        }

        // Set up the Bugsnag client
        $this->bugsnagClient->setReleaseStage($releaseStageClass->get());
        $this->bugsnagClient->setNotifyReleaseStages($container->getParameter('bugsnag.notify_stages'));
        $this->bugsnagClient->setProjectRoot(realpath($container->getParameter('kernel.root_dir').'/..'));

        // If the proxy settings are configured, provide these to the Bugsnag client
        if ($container->hasParameter('bugsnag.proxy')) {
            $this->bugsnagClient->setProxySettings($container->getParameter('bugsnag.proxy'));
        }

        // app version
        if ($container->hasParameter('bugsnag.app_version')) {
            $this->bugsnagClient->setAppVersion($container->getParameter('bugsnag.app_version'));
        }

        // Set up result array
        $metaData = array(
            'Symfony' => array()
        );

        // Get and add controller information, if available
        if ($container->isScopeActive('request')) {
            $request = $container->get('request');
            $controller = $request->attributes->get('_controller');

            if ($controller !== null) {
                $metaData['Symfony'] = array('Controller' => $controller);
            }

            $metaData['Symfony']['Route'] = $request->get('_route');
            $this->bugsnagClient->setMetaData($metaData);
        }
    }

    /**
     * Deal with Exceptions
     *
     * @param \Exception $exception
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
     * @param string $message  Error message
     * @param array  $metadata Metadata to be provided
     */
    public function notifyOnError($message, Array $metadata = null)
    {
        if ($this->enabled) {
            $this->bugsnagClient->notifyError('Error', $message, $metadata);
        }
    }
}

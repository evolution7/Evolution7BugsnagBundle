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
class Client
{
    protected $enabled = false;
    private $bugsnagClient;

    /**
     * @param string $apiKey
     * @param ReleaseStageInterface $releaseStageClass
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct($apiKey, ReleaseStageInterface $releaseStageClass, ContainerInterface $container)
    {
        if (!$apiKey) {
            return;
        }

        $this->enabled = true;
        $request = $container->get('request');

        // Set up the Bugsnag client
        $this->bugsnagClient = new \Bugsnag_Client($apiKey);
        $this->bugsnagClient->setReleaseStage($releaseStageClass->get());
        $this->bugsnagClient->setNotifyReleaseStages($container->getParameter('bugsnag.notify_stages'));
        $this->bugsnagClient->setProjectRoot(realpath($container->getParameter('kernel.root_dir').'/..'));

        if ($container->hasParameter('bugsnag.proxy')) {
            $this->bugsnagClient->setProxySettings($container->getParameter('bugsnag.proxy'));
        }

        // Set up result array
        $metaData = array(
            'Symfony' => array()
        );

        // Get and add controller information, if available
        $controller = $request->attributes->get('_controller');
        if ($controller !== null)
        {
            $metaData['Symfony'] = array('Controller' => $controller);
        }
        $metaData['Symfony']['Route'] = $request->get('_route');
        $this->bugsnagClient->setMetaData($metaData);
    }

    public function notifyOnException(\Exception $exception)
    {
        if ($this->enabled) {
            $this->bugsnagClient->notifyException($exception);
        }
    }

    public function notifyOnError($message, Array $metadata = null)
    {
        if ($this->enabled) {
            $this->bugsnagClient->notifyError('Error', $message, $metadata);
        }
    }
}

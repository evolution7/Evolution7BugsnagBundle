<?php
/*
 * This file is part of the Evolution7BugsnagBundle.
 *
 * (c) Evolution 7 <http://www.evolution7.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Evolution7\BugsnagBundle\Tests\Bugsnag;

use Evolution7\BugsnagBundle\Bugsnag\ClientLoader;

class ClientLoaderTest extends \PHPUnit_Framework_TestCase
{

    private $bugsnagClient;
    private $releaseStage;
    private $container;

    /**
     * @dataProvider settingsWorkForEnabledProvider
     */
    public function testSettingsWorkForEnabled($settings, $result, $reason)
    {
        $this->container->expects($this->any())
                    ->method('getParameter')
                    ->will($this->returnValueMap($settings));

        $client = new ClientLoader($this->bugsnagClient, $this->releaseStage, $this->container);

        $reflector = new \ReflectionClass($client);
        $toCheck = $reflector->getProperty('enabled');
        $toCheck->setAccessible(true);
        $this->assertEquals($result, $toCheck->getValue($client), $reason);
    }

    public function settingsWorkForEnabledProvider()
    {
        return array(
            array(
                array(
                    array('bugsnag.report_in_dev', false),
                    array('kernel.environment', 'dev'),
                    array('bugsnag.notify_stages', array('staging', 'production')),
                    array('kernel.root_dir', __DIR__)
                ),
                false,
                'In dev mode and report_in_dev false means disabled'
            ),
            array(
                array(
                    array('bugsnag.report_in_dev', true),
                    array('kernel.environment', 'dev'),
                    array('bugsnag.notify_stages', array('staging', 'production')),
                    array('kernel.root_dir', __DIR__)
                ),
                true,
                'In dev mode and report_in_dev true means enabled'
            ),
            array(
                array(
                    array('bugsnag.report_in_dev', false),
                    array('kernel.environment', 'prod'),
                    array('bugsnag.notify_stages', array('staging', 'production')),
                    array('kernel.root_dir', __DIR__)
                ),
                true,
                'In prod mode and report_in_dev false means enabled'
            ),
            array(
                array(
                    array('bugsnag.report_in_dev', true),
                    array('kernel.environment', 'prod'),
                    array('bugsnag.notify_stages', array('staging', 'production')),
                    array('kernel.root_dir', __DIR__)
                ),
                true,
                'In prod mode and report_in_dev true means enabled'
            )
        );
    }

    public function testProxySettingsNotPresent()
    {
        $settings = array(
                        array('bugsnag.report_in_dev', false),
                        array('kernel.environment', 'dev'),
                        array('bugsnag.notify_stages', array('staging', 'production')),
                        array('kernel.root_dir', __DIR__)
                    );

        $this->container->expects($this->any())
                    ->method('getParameter')
                    ->will($this->returnValueMap($settings));
        $this->bugsnagClient->expects($this->never())
                    ->method('setProxySettings');
        new ClientLoader($this->bugsnagClient, $this->releaseStage, $this->container);
    }

    public function testProxySettingsPresent()
    {
        $proxySettings = array('host' => 'testhost', 'port' => 42);
        $settings = array(
                        array('bugsnag.report_in_dev', false),
                        array('kernel.environment', 'dev'),
                        array('bugsnag.notify_stages', array('staging', 'production')),
                        array('kernel.root_dir', __DIR__),
                        array('bugsnag.proxy', $proxySettings)
                    );

        $this->container->expects($this->exactly(2))
                            ->method('hasParameter')
                            ->will($this->returnValue(true));

        $this->container->expects($this->any())
                    ->method('getParameter')
                    ->will($this->returnValueMap($settings));
        $this->bugsnagClient->expects($this->once())
                    ->method('setProxySettings');

        new ClientLoader($this->bugsnagClient, $this->releaseStage, $this->container);
    }

    /**
     * Tests that various settings are set in the client
     *
     * @dataProvider bugsnagClientMethodsCalledProvider
     */
    public function testBugsnagClientMethodsCalled($methodName)
    {
        $settings = array(
                        array('bugsnag.report_in_dev', false),
                        array('kernel.environment', 'dev'),
                        array('bugsnag.notify_stages', array('staging', 'production')),
                        array('kernel.root_dir', __DIR__)
                    );

        $this->container->expects($this->any())
                    ->method('getParameter')
                    ->will($this->returnValueMap($settings));
        $this->bugsnagClient->expects($this->once())
                    ->method($methodName);
        new ClientLoader($this->bugsnagClient, $this->releaseStage, $this->container);
    }

    public function bugsnagClientMethodsCalledProvider()
    {
        return array(
            array('setMetaData'),
            array('setReleaseStage'),
            array('setNotifyReleaseStages'),
            array('setProjectRoot')
            );
    }

    public function testNotifyOnExceptionNotEnabled()
    {
        $settings = array(
                        array('bugsnag.report_in_dev', false),
                        array('kernel.environment', 'dev'),
                        array('bugsnag.notify_stages', array('staging', 'production')),
                        array('kernel.root_dir', __DIR__)
                    );

        $this->container->expects($this->any())
                    ->method('getParameter')
                    ->will($this->returnValueMap($settings));
        $this->bugsnagClient->expects($this->never())
                    ->method('notifyException');
        $client = new ClientLoader($this->bugsnagClient, $this->releaseStage, $this->container);
        $client->notifyOnException(new \Exception('testmessage'));
    }

    public function testNotifyOnExceptionEnabled()
    {
        $settings = array(
                        array('bugsnag.report_in_dev', false),
                        array('kernel.environment', 'prod'),
                        array('bugsnag.notify_stages', array('staging', 'production')),
                        array('kernel.root_dir', __DIR__)
                    );

        $this->container->expects($this->any())
                    ->method('getParameter')
                    ->will($this->returnValueMap($settings));
        $this->bugsnagClient->expects($this->once())
                    ->method('notifyException');
        $client = new ClientLoader($this->bugsnagClient, $this->releaseStage, $this->container);
        $client->notifyOnException(new \Exception('testmessage'));
    }

    public function testNotifyOnErrorNotEnabled()
    {
        $settings = array(
                        array('bugsnag.report_in_dev', false),
                        array('kernel.environment', 'dev'),
                        array('bugsnag.notify_stages', array('staging', 'production')),
                        array('kernel.root_dir', __DIR__)
                    );

        $this->container->expects($this->any())
                    ->method('getParameter')
                    ->will($this->returnValueMap($settings));
        $this->bugsnagClient->expects($this->never())
                    ->method('notifyError');
        $client = new ClientLoader($this->bugsnagClient, $this->releaseStage, $this->container);
        $client->notifyOnError('message');
    }

    public function testNotifyOnErrorEnabled()
    {
        $settings = array(
                        array('bugsnag.report_in_dev', false),
                        array('kernel.environment', 'prod'),
                        array('bugsnag.notify_stages', array('staging', 'production')),
                        array('kernel.root_dir', __DIR__)
                    );

        $this->container->expects($this->any())
                    ->method('getParameter')
                    ->will($this->returnValueMap($settings));
        $this->bugsnagClient->expects($this->once())
                    ->method('notifyError');
        $client = new ClientLoader($this->bugsnagClient, $this->releaseStage, $this->container);
        $client->notifyOnError('message');
    }

    public function setup()
    {
        $this->bugsnagClient = $this->getMockBuilder('\Bugsnag_Client')
                                ->setConstructorArgs(array('testkey'))
                                ->getMock();
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->container->expects($this->once())
                    ->method('get')
                    ->will($this->returnValue($this->getMock('Symfony\Component\HttpFoundation\Request', null)));
        $this->releaseStage = $this->getMock('Evolution7\BugsnagBundle\ReleaseStage\ReleaseStageInterface');
        $this->releaseStage->expects($this->once())
                    ->method('get')
                    ->will($this->returnValue('test'));
    }

    public function tearDown()
    {
        $this->bugsnagClient = null;
        $this->container = null;
        $this->releaseStage = null;
    }
}
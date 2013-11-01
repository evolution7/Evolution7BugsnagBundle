<?php
/*
 * This file is part of the Evolution7BugsnagBundle.
 *
 * (c) Evolution 7 <http://www.evolution7.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evolution7\BugsnagBundle\Tests\ReleaseStage;

use Evolution7\BugsnagBundle\ReleaseStage\ReleaseStage;

/**
 * PHPUnit test for ReleaseStage
 * @author Arjen Schwarz <arjen@evolution7.com.au>
 */
class ReleaseStageTest extends \PHPUnit_Framework_TestCase
{
    private $object;

    public function testGetReleaseStageEnv()
    {
        putenv('RELEASE_STAGE=staging');
        $this->assertEquals('staging', $this->object->get(true));
    }

    public function testGetCached()
    {
        putenv('RELEASE_STAGE=staging');
        $this->assertEquals('staging', $this->object->get(true));
        putenv('RELEASE_STAGE=testing');
        $this->assertEquals('staging', $this->object->get(false));
    }

    public function testGetForced()
    {
        putenv('RELEASE_STAGE=staging');
        $this->assertEquals('staging', $this->object->get(true));
        putenv('RELEASE_STAGE=testing');
        $this->assertEquals('testing', $this->object->get(true));
    }

    public function testGetNoReleaseStageEnv()
    {
        $object = $this->getMockBuilder('Evolution7\BugsnagBundle\ReleaseStage\ReleaseStage')
                        ->setMethods(array('determineFromPath'))
                        ->getMock();
        $object->expects($this->once())
                ->method('determineFromPath')
                ->will($this->returnValue('testvalue'));
        $this->assertEquals('testvalue', $object->get(true));
    }

    public function testGetNoReleaseStageEnvDefaultToProduction()
    {
        $object = $this->getMockBuilder('Evolution7\BugsnagBundle\ReleaseStage\ReleaseStage')
                        ->setMethods(array('determineFromPath'))
                        ->getMock();
        $object->expects($this->once())
                ->method('determineFromPath')
                ->will($this->returnValue(''));
        $this->assertEquals('production', $object->get(true));
    }

    public function setup()
    {
        $this->object = new ReleaseStage();
        //The following will ensure the release stage environment is clean every time
        putenv('RELEASE_STAGE=fakeenvironment');
    }

    public function tearDown()
    {
        $this->object = null;
    }
}
<?php
/*
 * This file is part of the Evolution7BugsnagBundle.
 *
 * (c) Evolution 7 <http://www.evolution7.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Evolution7\BugsnagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Default controller, used for testing errors
 */
class DefaultController extends Controller
{
    /**
     * Throws an exception to test Bugsnag exceptions
     *
     * @throws \Exception
     */
    public function exceptionAction()
    {
        throw new \Exception('Exception for testing Bugsnag integration');
    }

    /**
     * Throws a fatal error to test Bugsnag exceptions
     */
    public function errorAction()
    {
        $testObject = new \stdClass();
        $testObject->throwMeAnErrorForBugsnag();
    }
}

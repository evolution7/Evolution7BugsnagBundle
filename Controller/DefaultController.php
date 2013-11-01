<?php
/**
 * Controller class, used for error testing
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

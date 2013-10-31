<?php

namespace Evolution7\BugsnagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function exceptionAction()
    {
        throw new \Exception('Exception for testing Bugsnag integration');
    }

    public function errorAction()
    {
        $testObject = new \stdClass();
        $testObject->throwMeAnErrorForBugsnag();
    }
}

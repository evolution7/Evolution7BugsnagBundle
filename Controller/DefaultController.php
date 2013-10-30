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
        trigger_error('Error for testing Bugsnag integration', E_USER_ERROR);
    }
}

<?php

namespace Evolution7\BugsnagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function testErrorAction()
    {
        throw new \Exception('Error for testing Bugsnag integration');
    }
}

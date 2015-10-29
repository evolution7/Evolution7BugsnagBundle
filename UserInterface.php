<?php

namespace Evolution7\BugsnagBundle;

interface UserInterface
{

    /**
     * The Method must return an array with all userdata that should be sent to
     * Bugsnag.
     *
     * Note: the array keys "id", "name" and "email" will be searchable!
     *
     * @return array
     */
    public function get();

}

<?php
namespace Evolution7\BugsnagBundle\ReleaseStage;

/**
 * Interface for ReleaseStage classes
 *
 * These classes are used to determine which release stage the application
 * is deployed in
 */
interface ReleaseStageInterface
{
    /**
     * Returns a textual description of the release stage
     *
     * @return string a textual description of the release stage
     */
    public function get();
}
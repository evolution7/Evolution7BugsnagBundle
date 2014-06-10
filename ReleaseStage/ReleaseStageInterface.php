<?php
/*
 * This file is part of the Evolution7BugsnagBundle.
 *
 * (c) Evolution 7 <http://www.evolution7.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Evolution7\BugsnagBundle\ReleaseStage;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Interface for ReleaseStage classes
 *
 * These classes are used to determine which release stage the application
 * is deployed in
 */
interface ReleaseStageInterface extends ContainerAwareInterface
{
    /**
     * Returns a textual description of the release stage
     *
     * @param boolean $force Bypass caching and determine anew
     *
     * @return string a textual description of the release stage
     */
    public function get($force = false);
}
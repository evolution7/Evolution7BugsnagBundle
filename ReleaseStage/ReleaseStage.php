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
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The evReleaseStage class is responsible for determining the environment/stage
 * of the current release.
 *
 * There are currently four valid release stages:
 * - development
 * - testing
 * - staging
 * - production
 */
class ReleaseStage implements ReleaseStageInterface, ContainerAwareInterface
{

    const DEVELOPMENT = 'development';
    const TESTING     = 'testing';
    const STAGING     = 'staging';
    const PRODUCTION  = 'production';

    protected static $current;

    /** @var  ContainerInterface */
    protected $container;

    /**
     * Get release stage
     *
     * @param boolean $force Bypass any possible caching
     *
     * @return string textual representation of the current release stage
     */
    public function get($force = false)
    {
        // Check if current set
        if (is_null(self::$current) || $force) {
            // Get environment variable (if set and valid)
            $releaseStage = trim(getenv('RELEASE_STAGE'));
            $releaseStage = in_array($releaseStage, $this->getAll()) ? $releaseStage : null;

            // If environment variable is not set, try to detect from Symfony environment
            $releaseStage = $releaseStage ? : $this->determineFromSymfony();

            // If environment variable not set/valid, try to detect environment by url or path
            $releaseStage = $releaseStage ? : $this->determineFromPath();

            // If environment variable still not set, assume we are in production!
            $releaseStage = $releaseStage ? : self::PRODUCTION;

            self::$current = $releaseStage;
        }

        return self::$current;
    }

    /**
     * Get all release stages
     *
     * @return array $releaseStages
     */
    public function getAll()
    {
        return array(
            self::DEVELOPMENT => self::DEVELOPMENT,
            self::TESTING     => self::TESTING,
            self::STAGING     => self::STAGING,
            self::PRODUCTION  => self::PRODUCTION,
        );
    }

    /**
     * Check if release stage is development
     *
     * @return boolean $is_development
     */
    public function isDevelopment()
    {
        return $this->get() === self::DEVELOPMENT;
    }

    /**
     * Check if release stage is testing
     *
     * @return boolean $is_testing
     */
    public function isTesting()
    {
        return $this->get() === self::TESTING;
    }

    /**
     * Check if release stage is staging
     *
     * @return boolean $is_staging
     */
    public function isStaging()
    {
        return $this->get() === self::STAGING;
    }

    /**
     * Check if release stage is production
     *
     * @return boolean $is_production
     */
    public function isProduction()
    {
        return $this->get() === self::PRODUCTION;
    }

    /**
     * Determine the current environment based on the path
     *
     * @return string
     */
    public function determineFromPath()
    {
        $releaseStage = null;
        // Create paths variable with host name, document root and file path
        $paths = __DIR__
            . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '')
            . (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '');

        // Now check if paths variable contains "stage" or "staging" keywords
        if (strpos($paths, 'stage') !== false || strpos($paths, 'staging') !== false) {
            // Set release stage to staging
            $releaseStage = self::STAGING;
        } elseif ((strpos(__FILE__, '/home') !== false && strpos(__FILE__, 'vhosts') !== false)
            || strpos($paths, '.local') !== false
            || file_exists('/home/vagrant')
        ) {
            //Check for dev environment that works with cli scripts
            $releaseStage = self::DEVELOPMENT;
        }

        return $releaseStage;
    }

    /**
     * Determine the current environment based on Symfony2 container
     */
    public function determineFromSymfony()
    {

        if ($this->container->get('kernel')->getEnvironment() == 'dev') {
            return self::DEVELOPMENT;
        }

        return null;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
<?php
namespace Evolution7\BugsnagBundle\ReleaseStage;

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
class ReleaseStage implements ReleaseStageInterface
{

  const DEVELOPMENT = 'development';
  const TESTING     = 'testing';
  const STAGING     = 'staging';
  const PRODUCTION  = 'production';

  protected static $current;

  /**
   * Get release stage
   *
   * @SuppressWarnings(PHPMD.Superglobals)
   * @SuppressWarnings(PHPMD.NPathComplexity)
   * @SuppressWarnings(PHPMD.CamelCaseVariableName)
   *
   * @return string $releaseStage
   */
  public function get()
  {
    // Check if current set
    if (is_null(self::$current)) {
      // Get environment variable (if set and valid)
      $releaseStage = trim(getenv('RELEASE_STAGE'));
      $releaseStage = (in_array($releaseStage, $this->getAll())) ? $releaseStage : null;

      // If environment variable not set/valid, try to detect staging environment by url or path
      if (is_null($releaseStage)) {
        // Create paths variable with host name, document root and file path
        $paths = __FILE__
          . (array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : '')
          . (array_key_exists('DOCUMENT_ROOT', $_SERVER) ? $_SERVER['DOCUMENT_ROOT'] : '');

        // Now check if paths variable contains "stage" or "staging" keywords
        if (strpos($paths, 'stage') !== false || strpos($paths, 'staging') !== false) {
          // Set release stage to staging
          $releaseStage = self::STAGING;
        } elseif ((strpos(__FILE__, '/home') !== false && strpos(__FILE__, 'vhosts') !== false) || file_exists('/home/vagrant')) {
          //Check for dev environment that works with cli scripts
          $releaseStage = self::DEVELOPMENT;
        }
      }

      // If environment variable still not set, assume we are in production!
      $releaseStage = is_null($releaseStage) ? self::PRODUCTION : $releaseStage;

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

}

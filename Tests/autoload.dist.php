<?php
/*
 * This file is part of the Evolution7BugsnagBundle.
 *
 * (c) Evolution 7 <http://www.evolution7.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!$loader = include __DIR__.'/../vendor/autoload.php') {
    $nl = PHP_SAPI === 'cli' ? PHP_EOL : '<br />';
    echo "$nl$nl";
    if (is_writable(dirname(__DIR__)) && $installer = @file_get_contents('http://getcomposer.org/installer')) {
        echo 'You must set up the project dependencies.'.$nl;
        $installerPath = dirname(__DIR__).'/install-composer.php';
        file_put_contents($installerPath, $installer);
        echo 'The composer installer has been downloaded in '.$installerPath.$nl;
        die('Run the following commands in '.dirname(__DIR__).':'.$nl.$nl.
            'php install-composer.php'.$nl.
            'php composer.phar install'.$nl);
    }
    die('You must set up the project dependencies.'.$nl.
        'Run the following commands in '.dirname(__DIR__).':'.$nl.$nl.
        'curl -s http://getcomposer.org/installer | php'.$nl.
        'php composer.phar install'.$nl);
}

spl_autoload_register(function($class) {
    if (0 === strpos($class, 'Evolution7\\BugsnagBundle\\')) {
        $path = __DIR__.'/../'.implode('/', array_slice(explode('\\', $class), 2)).'.php';
        if (!stream_resolve_include_path($path)) {
            return false;
        }
        require_once $path;

        return true;
    }
});

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

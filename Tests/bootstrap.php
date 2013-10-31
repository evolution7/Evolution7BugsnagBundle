<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (file_exists($file = __DIR__.'/autoload.php')) {
    require_once $file;
} elseif (file_exists($file = __DIR__.'/autoload.dist.php')) {
    require_once $file;
}

// if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
//     throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
// }
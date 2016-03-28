[![Latest Stable Version](https://poser.pugx.org/evolution7/bugsnag-bundle/v/stable)](https://packagist.org/packages/evolution7/bugsnag-bundle) [![Total Downloads](https://poser.pugx.org/evolution7/bugsnag-bundle/downloads)](https://packagist.org/packages/evolution7/bugsnag-bundle) [![Latest Unstable Version](https://poser.pugx.org/evolution7/bugsnag-bundle/v/unstable)](https://packagist.org/packages/evolution7/bugsnag-bundle) [![License](https://poser.pugx.org/evolution7/bugsnag-bundle/license)](https://packagist.org/packages/evolution7/bugsnag-bundle)

# Evolution7BugsnagBundle #
Enables Bugsnag integration into your Symfony application, using the [bugsnag-php](https://github.com/bugsnag/bugsnag-php) 2.x library from Bugsnag.

# Installation #
The recommended way of installing this bundle is using [Composer](http://getcomposer.org/). 

Add this repository to your composer information using the following command

```bash
composer require "evolution7/bugsnag-bundle:~2.0"
```

Add the bundle to your AppKernel.php:

```php
$bundles = array(
    //Other Bundles
    new Evolution7\BugsnagBundle\BugsnagBundle(),
```

Define your Bugsnag API key in the config.yml

```yml
bugsnag:
    api_key: YOUR-API-KEY
```

# Usage #
After the installation the bundle works without any additional settings required, but you can tweak some settings.

## Enabled Stages ##
You can set for which Symfony environments (`kernel.environment`) you want Bugsnag to be enabled. This is done through the enabled_stages setting:

```yml
bugsnag:
    enabled_stages: [dev, prod, staging]
```

These environments should match the environment as set in your application's `web/app.php`, `web/app_dev.php` and/or `app/console`. The default is to report bugs for the `prod` environment only.


## Notify Stages ##
You can set for which environments you want Bugsnag to get error reports. This is done with the notify_stages setting:

```yml
bugsnag:
    notify_stages: [development, staging, production]
```

The default is to report bugs in staging and production environments.


## Proxy ##
If your server requires you to access Bugsnag through a proxy, you can set this up easily as well. Just use the following example to configure the settings you need in your config.yml:

```yml
bugsnag:
    proxy:
        host: www.bugsnag.com
        port: 42
        user: username
        password: password
```

The only of these settings that is mandatory is the host, all others can be left out if they aren't required.

## AppVersion ##
If you tag your app releases with version numbers, Bugsnag can display these on your dashboard if you set this:

```yml
bugsnag:
    app_version: v1.2.3
```

## Testing ##
Included in the bundle is a controller that will allow you to test if your site is hooked up correctly. Just add the following to your routing.yml:

```yml
evolution7_bugsnag_bundle:
    resource: "@BugsnagBundle/Resources/config/routing.yml"
    prefix:   /bugsnagtest
```

And then afterwards you can access `your.domain/bugsnagtest/exception` and `your.domain/bugsnagtest/error` which should then send errors to your configured Bugsnag project.

# Advanced Usage #

## Release Stage Class ##
Bugsnag allows you to determine which release stage you are currently in, the Evolution7BugsnagBundle uses a ReleaseStage class for this which determines this based on the path. Depending on your setup you might want to have a different way of determining this, in which case it is possible to override this by providing your own ReleaseStage class.
You can implement a class that implements the `Evolution7\BugsnagBundle\ReleaseStage\ReleaseStageInterface` and provide its name as a parameter in your config.yml

```yml
bugsnag:
    release_stage:
        class: Your\Name\Space\ClassName
```

## User Information ##
Bugsnag gives the possibility to give userdata as additional information to a request. If you give an id, name or email these fields will be searchable. Other fields are allowed
but not searchable - they will only be displayed. The bundle allows to set a user to array converter as a service which will be used to send user data.
The given service must be an instance of \Evolution7\BugsnagBundle\UserInterface

```php
<?php
# src/AppBundle/BugsnagUser.php
namespace AppBundle;

use Evolution7\BugsnagBundle\UserInterface as BugsnagUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

class BugsnagUser implements BugsnagUserInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $token;

    /**
     * @param TokenStorageInterface $token
     */
    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token->getToken();
    }

    /**
     * @inheritdoc
     */
    public function getUserAsArray()
    {
        if (
            is_null($this->token)
            || !$this->token->isAuthenticated()
            || !$this->token->getUser() instanceof SymfonyUserInterface
        ) {
            return [];
        }

        $user = $this->token->getUser();

        return [
            'id' => $user->getId(),
            'name' => $user->getUsername(),
            'email' => $user->getEmail()
        ];
    }
}
```

```yml
# services.yml
services:
  app.bugsnag_user:
    class: AppBundle\BugsnagUser
    arguments: [@security.token_storage]
```

```yml
# app/config/config.yml
bugsnag:
    user: app.bugsnag_user
```

# Contributing #

* Fork it on Github
* Commit and push until you are happy
* Run the tests to make sure they all pass: composer install && ./vendor/bin/phpunit
* Make a pull request
* Thanks!

# Acknowledgement #
Parts of this code are based on the [bugsnag-php-symfony Bundle](https://github.com/wrep/bugsnag-php-symfony)

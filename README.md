# Evolution7 Bugsnag Bundle #
Enables Bugsnag integration into your Symfony2 application, using the [bugsnag-php](https://github.com/bugsnag/bugsnag-php) 2.x library from Bugsnag.

# Installation #
Composer (both this and bugsnag-php)

Add the bundle to your AppKernel.php:

```php
$bundles = array(
    //Other Bundles
    new Evolution7\BugsnagBundle\Evolution7BugsnagBundle(),
```

Define your Bugsnag API key in the config.yml

```yml
parameters:
    bugsnag.api_key: YOUR-API-KEY
```

# Usage #
After the installation the bundle works without any additional settings required, but you can tweak some settings.

## Notify Stages ##
You can set for which environments you want Bugsnag to get error reports. This is done with the notify_stages setting:

```yml
parameters:
    bugsnag.notify_stages: [development, staging, production]
```

The default is to report bugs in staging and production environments.

## Proxy ##
If your server requires you to access Bugsnag through a proxy, you can set this up easily as well. Just use the following example to configure the settings you need in your config.yml:

```yml
parameters:
    bugsnag.proxy:
        host: www.bugsnag.com
        port: 42
        user: username
        password: password
```

The only of these settings that is mandatory is the host, all others can be left out if they aren't required.

# Advanced Usage #

## Release Stage Class ##
Bugsnag allows you to determine which release stage you are currently in, the Evolution7BugsnagBundle uses a ReleaseStage class for this which determines this based on the path. Depending on your setup you might want to have a different way of determining this, in which case it is possible to override this by providing your own ReleaseStage class.
You can implement a class that implements the `Evolution7\BugsnagBundle\ReleaseStage\ReleaseStageInterface` and provide its name as a parameter in your config.yml

```yml
parameters:
    bugsnag.release_stage.class: Your\Namespace\ReleaseStage
```

# TODO #

* Composer/Packagist
* Unit tests
* Jenkins integration (Maybe Travis for Github?)

Parts of this code are based on the [bugsnag-php-symfony Bundle](https://github.com/wrep/bugsnag-php-symfony)
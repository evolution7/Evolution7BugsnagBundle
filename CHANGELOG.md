1.3.0 / 2015-09-15
==================

  * Introduce bugsnag.enabled_stages setting. (Thanks to [vicdelfant](https://github.com/vicdelfant))

1.2.1 / 2015-07-22
==================

  * Change CI build to use Docker containers
  * Make sure that json content types bass over their parameters properly. (Thanks to [dbltr](https://github.com/dbtlr))

1.2.0 / 2015-04-28
==================

  * Make sure the shutdowns get an error severity. (Thanks to [dbltr](https://github.com/dbtl://github.com/dbtlr))
  * Add in the ability to pass in metadata and set the default exception severity to error.

# 1.1.3

* Recognize `.dev` domains as development servers
* Removed the filesystem check for `/home/vagrant` as it can potentially cause issues with open_basedir
* Added the [evolution7/qa-tools](https://github.com/evolution7/qa-tools) as a dev dependency

# 1.1.2

* Bugfix, running through the PHP built-in webserver was seen as being the production environment.

# 1.1.1

* Bugfix, where reports would fail if `notify_stages` was not defined.
* Updated PHPDoc

# 1.1.0

* Added support for app version (thanks to [ROMOPAT](https://github.com/ROMOPAT))
* Added Changelog

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
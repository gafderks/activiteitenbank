# Activiteitenbank
Database management system for Scouting Activities.

## Installation
* Clone the repository.
* Install dependencies using composer: `php composer.phar install`
* Create a new MySQL database.
* Copy `config.dist.php` to `config.php` and configure the database settings in it.
* Run `./vendor/bin/doctrine-module orm:schema-tool:create` to populate the database.
* Install dependencies using bower: `bower install`

## Translation
The website is generated using `gettext`. By default, all strings are written in English.

### Cache generator
In the main directory of the project, there is a `generate-cache.php` script. If this script is executed from the
command line, all strings which are translated by the translated will be put in the cache folder
`/view/{template}/cache`. With an editor like POEdit, these files can be put into a catalog and translation strings can
be extracted.
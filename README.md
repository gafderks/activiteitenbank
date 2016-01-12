# Activiteitenbank

## Installation
* Clone the repository.
* Install dependencies using composer: `php composer.phar install`
* Create a new MySQL database.
* Copy `config.dist.php` to `config.php` and configure the database settings in it.
* Run `./vendor/bin/doctrine-module orm:schema-tool:create` to populate the database.

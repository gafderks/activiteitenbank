# Activiteitenbank
Database management system for Scouting Activities.

## Installation
* Clone the repository.
* Install dependencies using composer: `php composer.phar install`
* Create a new MySQL database.
* Copy `config.dist.php` to `config.php` and configure the database settings in it.
* Run `./vendor/bin/doctrine orm:schema-tool:create` to populate the database.
* Install dependencies using bower: `bower install`
* Make sure that `/upload`, `/temp`, and `/view/flatly/cache` are writeable by PHP.

## Translation
The website is generated using `gettext`. By default, all strings are written in English.

### Cache generator
In the main directory of the project, there is a `generate-cache.php` script. If this script is executed from the
command line, all strings which are translated by the translated will be put in the cache folder
`/view/{template}/cache`. With an editor like POEdit, these files can be put into a catalog and translation strings can
be extracted.

### JavaScript Translator
For obtaining translated strings inside of JavaScript files, a Translator object is used. The Translator object is
created dynamically using Twig. The object contains a dictionary of strings that are translated using `gettext`. To
use translated strings in JavaScript, you need to add the original string to `/view/{template}/js/translator.twig` and
then use `Translator.translate("Original string")` in the JavaScript.

## API
This application provides an API that uses [JSON Web Tokens (JWT)](http://jwt.io/) for authentication.
For all API-routes that are non-public a token need to be supplied using the Authorization header.

For this application, JWTs have the following layout:
```json
 {
   "iss": "{{domain}}",
   "iat": 1457141623,
   "exp": 1457228023,
   "sub": 1,
   "scopes": {
     "activity": {
       "actions": [
         "edit",
         "delete"
       ]
     }
   }
 }
```
Tokens are signed and base-64 encoded. An example of a header is:
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ7e2RvbWFpbn19IiwiaWF0IjoxNDU3MTQxNjIzLCJleHAiOjE0NTcyMjgwMjMsInN1YiI6MSwic2NvcGVzIjp7ImFjdGl2aXR5Ijp7ImFjdGlvbnMiOlsiZWRpdCIsImRlbGV0ZSJdfX19.2lSXvVWWE5bgYcCY95eooRN11GSP4EQTHvX_AWMJaO4
```
The scope is added to the token to be able to limit the capabilities of it. Be aware that also the permissions for the
subject of the token are checked, meaning that a token can never grant permissions that the subject does not have.
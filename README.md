# CodeIgniter 4

## Server Requirements

PHP version 7.4 or higher is required, with the following extensions installed:


- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- xml (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)

## Running CodeIgniter Tests

Information on running the CodeIgniter test suite can be found in the [README.md](tests/README.md) file in the tests directory.

----------------------------------------------------------------------
## PUBLICAR en server compartido:
PHP >= 7.4.

## cors:
https://github.com/agungsugiarto/codeigniter4-cors
composer require agungsugiarto/codeigniter4-cors

## modificar .env
CI_ENVIRONMENT = production
app.baseURL = 'https://sprint09.cerolab.com/'

database.default.hostname = etzpmvnbob.mysql.db
 ...
database.default.DBDriver = MySQLi

## modificar public/.htaccess 
RewriteRule ^(.*)$ index.php?/$1 [L]

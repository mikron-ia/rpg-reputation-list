# RPG Reputation List
[![Build Status](https://travis-ci.org/mikron-ia/rpg-reputation-list.svg?branch=master)](https://travis-ci.org/mikron-ia/rpg-reputation-list)
[![Code Climate](https://codeclimate.com/github/mikron-ia/rpg-reputation-list/badges/gpa.svg)](https://codeclimate.com/github/mikron-ia/rpg-reputation-list)
[![Test Coverage](https://codeclimate.com/github/mikron-ia/rpg-reputation-list/badges/coverage.svg)](https://codeclimate.com/github/mikron-ia/rpg-reputation-list/coverage)

Universal reputation collection and display system for RPG stories. For details, please see [project's wiki](https://github.com/mikron-ia/rpg-reputation-list/wiki)

## Installation guide
1. Clone the project via `git clone` to a desired directory of a web server
2. Run `composer install` in the project directory
3. Create a new database on the database server and run `mysql_base_start.sql` in it
4. Copy or rename the `deployment.php.example` file into `deployment.php`
5. Copy or rename the `epic.php.example` file into `epic.php`
6. Ensure there is a correct `config/data/{system code}.php` file for the system you wish to model reputation for
7. Enter desired configuration, ie.:
    * `deployment.php` - database information and authentication data (method and key[s])
    * `epic.php` - system code and - optionally - your own reputation list in correct format (see example system config files)
8. Set document root to `public/` or redirect requests there
9. Set the project address as data source in a data retriever you are using

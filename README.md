Config Loader
=============

[![Latest Stable Version](https://poser.pugx.org/garoevans/config_loader/v/stable.svg)](https://packagist.org/packages/garoevans/config_loader) [![Build Status](https://travis-ci.org/garoevans/config_loader.svg?branch=master)](https://travis-ci.org/garoevans/config_loader) [![License](https://poser.pugx.org/garoevans/config_loader/license.svg)](https://packagist.org/packages/garoevans/config_loader)

Installation
------------

This library requires PHP 5.3 or later, and is installable and autoloadable via Composer as [garoevans/config_loader](https://packagist.org/packages/garoevans/config_loader).

Usage
-----

```php
use Garoevans\ConfigLoader;

$config = ConfigLoader('config_directory', 'ini_file_name.ini');
$config->load();

// Gets the ini section called 'db' or returns an empty array
$config->get('db', array());

// Gets the value of 'host' from the 'db' section or returns an empty string
$config->get('db/host', '');
```

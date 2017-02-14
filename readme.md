# Baytek Laravel Content Webpage
[![Laravel](https://img.shields.io/badge/Laravel-~5.3-orange.svg?style=flat-square)](http://laravel.com)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)
[![PHP](https://img.shields.io/badge/PHP-%3E=5.6.4-green.svg)](http://www.php.net/ChangeLog-5.php#5.6.4)

## Installation

#### Composer

Currently this project is not publicly available. You must add a repository object in your composer.json file. You must also have SSH keys setup.

```javascript
"repositories": [
    {
        "type": "git",
        "url": "ssh://sls@slsapp.com:1234/baytek/laravel-content-webpage.git"
    }
],
```

Add the following `require` to your composer.json file:

```javascript
"baytek/laravel-content-webpage": "dev-master"
```

Lastly run:

`composer update`

## Configuration

Currently there is no real configuration other than configurations that are exposed using the `laravel-settings` package.

Soon views and generic configurations will be added. Details will be added here.

## Dependencies

This project depends on:

```javascript
"baytek/laravel-content": "dev-master",
"baytek/laravel-settings": "dev-master",
```

## Packing Info

This package provides two routes, a route resource mapped to `admin/webpage` and a wildcard route at the index.


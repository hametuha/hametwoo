# hametwoo

A utility classes for WooCommerce development.

[![Build Status](https://travis-ci.org/hametuha/hametwoo.svg)](https://travis-ci.org/hametuha/hametwoo)

## How to Use

Include via composer. In your composer.json:

```
{
  "require": {
    "hametuha/hametwoo": "~0.9"
  }
}
```

This library is useful for making Payment Gateways.

## Development

Composer, Node.js, and Docker are required. At first, clone GitHub repository.

```
git clone git@github.com:hametuha/hametwoo.git
```

Then install dependencies.

```
composer install
npm install
```

Run local environment.

```
npm start
```

Run test.

```
# PHP Unit test in Docker.
npm test
# PHP Code Sniffer
composer lint
# PHP Code Beautifier
composer format
```

To enable mailhog for debug, follow the instruction below.

```
# Get docker container ID.
# Notice: run npm start before this step.
npm run path
# You will get container ID at the base name e.g. 0597019337936df00cda1cf5a15016e0
# Save it as .wp_install_path
touch .wp_install_path
echo 0597019337936df00cda1cf5a15016e0 > .wp_install_path
# Restart docker.
npm run update
# Open http://localhost:8025
```

## License

GPL 3.0 and later.

## Change Log

### 0.8.5

- Fix custom email to automatically fired.

### 0.8.4

- Add custom email for cancellation.

### 0.8.2

- Add dependency check `Compatibility::check_dependency( $plugin_files_array )`.
- Add utility functions for get post data for Gateway API.

### 0.8.0

First release.

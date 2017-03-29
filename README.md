# hametwoo
A utility classes for WooCommerce development.

[![Build Status](https://travis-ci.org/hametuha/hametwoo.svg)](https://travis-ci.org/hametuha/hametwoo)

## How to Use

Include via composer. In your compsoer.json:

```
{
  "require": {
    "hametuha/hametwoo": "~0.8"
  }
}
```

This library is useful for making Payment Gateways.

## License

GPL 3.0 and later.

## Change Log

### 0.8.2

- Add dependency check `Compatibility::check_dependency( $plugin_files_array )`.
- Add utility functions for get post data for Gateway API.

### 0.8.0

First release.

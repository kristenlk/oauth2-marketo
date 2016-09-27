# Marketo Provider for OAuth 2.0 Client
[![Latest Version](https://img.shields.io/github/release/kristenlk/oauth2-marketo.svg?style=flat-square)](https://github.com/kristenlk/oauth2-marketo/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/kristenlk/oauth2-marketo/master.svg?style=flat-square)](https://travis-ci.org/kristenlk/oauth2-marketo)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/kristenlk/oauth2-marketo.svg?style=flat-square)](https://scrutinizer-ci.com/g/kristenlk/oauth2-marketo/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/kristenlk/oauth2-marketo.svg?style=flat-square)](https://scrutinizer-ci.com/g/kristenlk/oauth2-marketo)
[![VersionEye](https://img.shields.io/versioneye/d/ruby/rails.svg?style=flat-square)](https://www.versioneye.com/user/projects/5601995af5f2eb001700061d)
[![Total Downloads](https://img.shields.io/packagist/dt/kristenlk/oauth2-marketo.svg?style=flat-square)](https://packagist.org/packages/kristenlk/oauth2-marketo)

This package provides Marketo OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require kristenlk/oauth2-marketo
```

## Usage

Usage is the same as The League's OAuth client, using `\Kristenlk\OAuth2\Client\Provider\Marketo` as the provider.

### Authorization Code Flow

Marketo's REST APIs are authenticated with two-legged OAuth 2.0. We don't need to pass a redirectUri to the provider, but we do need to include a base url that will be used to request an access token.

```php
<?php
$provider = new \Kristenlk\OAuth2\Client\Provider\Marketo([
    'clientId'          => '{marketo-client-id}',
    'clientSecret'      => '{marketo-client-secret}',
    'baseUrl'           => 'https://your-base-url.mktorest.com'
]);

// Try to get an access token (using the client credentials grant)
$token = $provider->getAccessToken('client_credentials');

// Use this to interact with an API on the users behalf
echo $token->getToken();


```

If the access token expires, we can just call getAccessToken() again.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/kristenlk/oauth2-marketo/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Kristen Kehlenbeck](https://github.com/kristenlk)
- [All Contributors](https://github.com/kristenlk/oauth2-marketo/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/kristenlk/oauth2-marketo/blob/master/LICENSE) for more information.
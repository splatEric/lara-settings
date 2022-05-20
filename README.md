# LaraSettings

[![Latest Stable Version](https://poser.pugx.org/camc/lara-settings/v/stable?format=flat-square)](https://packagist.org/packages/camc/lara-settings)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/splatEric/lara-settings/run-tests?label=tests)

This Laravel package provides a simple abstraction for storing application settings in the database. It mimics the basic behaviour of the `config` helper and `Config` facades of core Laravel, but allows the values to be stored in the database where they can be more dynamically managed.

```php
Camc\LaraSettings\LaraSetting::create(['key' => 'foo', 'value' => 'bar']);
// with a helper
lara_settings('foo'); // bar
// or with the facade
LaraSettings::get('foo'); // bar
```

An admin interface, or using tools like [Tinkerwell](https://tinkerwell.app) will allow those settings to be updated on any application instance.

```php
LaraSettings::set('foo', 'baz');
lara_settings('foo'); // baz
```
Underneath, values are cached to minimise the database hit of retrieving the values. 

Dot notation is supported for retrieving values

```php
Camc\LaraSettings\LaraSetting::create([
    'key' => 'foo', 
    'value' => ['bar' => 'baz']
]);
lara_settings('foo.bar') // baz
```

and a default may be provided to the getter for null values or missing settings:

```php
Camc\LaraSettings\Models\LaraSetting::whereKey('foo')->first()->delete();
lara_settings('foo', 'deleted'); // deleted

// a setting instance will be created if not yet defined
LaraSettings::set('foo', null);
lara_settings('foo', 'null value'); // "null value"
```

## Installation

`composer require camc/lara-settings`

## Configuration

Configuration currently supports two settings:

1. `model_table` - defines the name of the table to be used by the `LaraSetting` model. Defaults to `lara_settings_settings`.
2. `cache_key` - the key which caches the settings store. Defaults to `lara-settings`.

The config file can be published via `artisan`:

`php artisan vendor:publish --provider=lara-settings-config`

## Usage

Beyond the examples shown above, seeding settings with initial values may be useful:

```php
// database/seeders/settings/default.php
<?php

return [
    'foo' => ['bar' => 'baz']
];
```
```php 
// database/seeders

/**
* Only load settings if the file is present
*/
class LaraSettingsSeeder extends Seeder
{
    public function run()
    {
        $settingsFile = require(__DIR__ . '/settings/default.php';
        if (file_exists($settingsFile)) {
            foreach (require($settingsFile) as $key => $value) {
                LaraSettings::set($key, $value);
            }
        }
    } 
}
```

By checking if the file exists or not, it's possible to not commit the settings file, and then upload a custom file for any given installation.

## Development

### Tests

`composer test`

### Linting

`composer format`

## Changelog

Please see [CHANGELOG](./CHANGELOG.md) for update history.

## Future development

### Admin support

The `LaraSetting` model has an unused `description` attribute which is a placeholder for storing descriptive text about the setting that could be displayed in an admin UI. This field is not intended for dynamic alteration, but an interface should be provided to make the presentation of settings in admin straightforward.

Given the wide variety of ways in which admin could be implemented, it's unlikely that UI will be added to the package, however a command line tool may prove useful. 

### Model validation

The `LaraSetting` accepts a string or an array as values. It might be useful to provide mechanisms to validate the content etc.

## License

The MIT License (MIT). Please see [License File](./LICENSE) for more information.

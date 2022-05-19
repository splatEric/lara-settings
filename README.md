# LaraSettings

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

## Development

### Tests

`composer test`

### Linting

`composer format`

## Changelog

Please see [CHANGELOG](./CHANGELOG.md) for update history.

## License

The MIT License (MIT). Please see [License File](./LICENSE) for more information.

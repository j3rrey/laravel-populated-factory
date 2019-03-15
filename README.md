[![Build Status](https://travis-ci.com/j3rrey/laravel-populated-factory.svg?branch=master)](https://travis-ci.com/j3rrey/laravel-populated-factory)[![Maintainability](https://api.codeclimate.com/v1/badges/4345ff2db5a328125e41/maintainability)](https://codeclimate.com/github/j3rrey/laravel-populated-factory/maintainability)
<a href="https://codeclimate.com/github/j3rrey/laravel-populated-factory/test_coverage"><img src="https://api.codeclimate.com/v1/badges/4345ff2db5a328125e41/test_coverage" /></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/github/license/hyperium/hyper.svg" /></a>

This is a fork from the Original

In this fork you can create factories based on directories.
For custom directories just pass in the Directory name.

```php
php artisan make:populated-factories Models
```

You will be asked if you want to create a factory for each Model found in the directory

Further development will be made. 
I'm planning to make this an all in one Model seed generator maybe even a new model generator with seeds etc included.

<p align="center"><img alt="Laravel Populated Factory" src="https://i.imgur.com/OEiucXg.png" width="500"></p>

<p align="center"><b>Laravel Populated Factory</b> provides an easy way to generate populated factories for models according to types & names of their columns.</p>

## Install

You can install this package via composer using this command:

```php
composer require J3rry/laravel-populated-factory
```

The package will automatically register itself.

## Usage

The only thing you need to do in order to generate a populated factory is to execute this command:

```php
php artisan make:populated-factory User
```

> This command assumes that the `User` model is in the `App` namespace. If your models are situated in another namespace (e.g. `App\Models`) you should specify them either as `Models\\User` or `\\App\\Models\\User`.

Here is the populated factory generated for the `User` model according to its column types & names.

```php
<?php

use Faker\Generator as Faker;

/** @var $factory \Illuminate\Database\Eloquent\Factory */

$factory->define(\App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $faker->dateTime,
        'password' => '$2y$10$uTDnsRa0h7wLppc8/vB9C.YqsrAZwhjCgLWjcmpbndTmyo1k5tbRC',
        'remember_token' => $faker->sha1,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
```

If you want a custom name for the factory, you need to pass it as the second argument like so:

```bash
php artisan make:populated-factory User AdminFactory
```

If you want to override the existent factory, you need to use `--force` flag like so:

```bash
php artisan make:populated-factory User --force
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

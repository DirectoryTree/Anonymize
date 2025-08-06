# Anonymize

[![Latest Version on Packagist](https://img.shields.io/packagist/v/directorytree/anonymize.svg?style=flat-square)](https://packagist.org/packages/directorytree/anonymize)
[![Tests](https://img.shields.io/github/actions/workflow/status/DirectoryTree/Anonymize/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/DirectoryTree/Anonymize/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/directorytree/anonymize.svg?style=flat-square)](https://packagist.org/packages/directorytree/anonymize)

Anonymize replaces sensitive model data with realistic fake data using Faker. Perfect for development environments, demos, and data sharing scenarios where you need to protect user privacy while maintaining data structure and relationships.

## Features

- **Privacy-First**: Automatically anonymize sensitive model attributes
- **Consistent Data**: Same model ID always generates the same fake data
- **Seamless Integration**: Works transparently with existing Eloquent models
- **Granular Control**: Enable/disable anonymization globally or per-model instance
- **Performance Optimized**: Intelligent caching prevents redundant fake data generation

## Requirements

- PHP >= 8.2
- Laravel >= 11

## Installation

You can install the Anonymize using Composer:

```bash
composer require directorytree/anonymize
```

## Usage

### Set Up Your Model

Implement the `Anonymizable` interface and use the `Anonymized` trait on your Eloquent model.

Then, define the attributes you want to anonymize in the `getAnonymizedAttributes()` method:

```php
<?php

namespace App\Models;

use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use DirectoryTree\Anonymize\Anonymized;
use DirectoryTree\Anonymize\Anonymizable;

class User extends Model implements Anonymizable
{
    use Anonymized;

    public function getAnonymizedAttributes(Generator $faker): array
    {
        return [
            'name' => $faker->name(),
            'email' => $faker->safeEmail(),
            'phone' => $faker->phoneNumber(),
            'address' => $faker->address(),
        ];
    }
}
```

The attributes returned by `getAnonymizedAttributes()` will replace its original attributes when anonymization is enabled.

Attributes that are not defined in the `getAnonymizedAttributes()` will not be anonymized, and will be returned as-is.

### Enable Anonymization

Somewhere within your application, enable anonymization:

> [!note]
> This is typically done within a service provider or middleware, and controlled with a session variable.

```php
use DirectoryTree\Anonymize\Facades\Anonymize;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (session('anonymize')) {
            Anonymize::enable();
        }
    }
}
```

### Controlling Anonymization

Control anonymization across your application using the `Anonymize` facade:

```php
use DirectoryTree\Anonymize\Facades\Anonymize;

// Enable anonymization
Anonymize::enable();

// Disable anonymization
Anonymize::disable();

// Check if anonymization is enabled
if (Anonymize::isEnabled()) {
    // Anonymization is active
}
```

### Consistent Fake Data

Anonymize ensures that the same model always generates the same fake data.

This makes browsing your application consistent and predictable:

```php
Anonymize::enable();

$user1 = User::find(1);
$user2 = User::find(1);

// Both instances will have identical fake data
$user1->name === $user2->name; // true
$user1->email === $user2->email; // true
```

Different models generate different fake data:

```php
$user1 = User::find(1);
$user2 = User::find(2);

// Different users get different fake data
$user1->name !== $user2->name; // true
$user1->email !== $user2->email; // true
```

### Custom Seed Generation

Override the seed generation logic for more control.

The seed is used to ensure consistent fake data generation:

```php
class User extends Model implements Anonymizable
{
    use Anonymized;

    public function getAnonymizableSeed(): string
    {
        return "my-custom-seed:{$this->id}";
    }

    public function getAnonymizedAttributes(Generator $faker): array
    {
        return [
            'name' => $faker->name(),
            'email' => $faker->safeEmail(),
        ];
    }
}
```

### Conditional Anonymization

Only anonymize specific attributes based on conditions:

```php
public function getAnonymizedAttributes(Generator $faker): array
{
    $attributes = [];

    // Always anonymize email
    $attributes['email'] = $faker->safeEmail();

    // Only anonymize name for non-admin users
    if (! $this->is_admin) {
        $attributes['name'] = $faker->name();
    }

    return $attributes;
}
```

## Testing

```bash
./vendor/bin/pest
```

## Benchmarking

```bash
./vendor/bin/phpbench run
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

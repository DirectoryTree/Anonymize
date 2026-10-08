<h1 align="center">Anonymize</h1>

<p align="center">Replace sensitive Eloquent model attributes with realistic fake data.</p>

<p align="center">
    <a href="https://github.com/DirectoryTree/Anonymize/actions/workflows/run-tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/DirectoryTree/Anonymize/run-tests.yml?branch=master&amp;style=flat-square" alt="Tests"></a>
    <a href="https://packagist.org/packages/directorytree/anonymize"><img src="https://img.shields.io/packagist/dt/directorytree/anonymize.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/directorytree/anonymize"><img src="https://img.shields.io/packagist/v/directorytree/anonymize.svg?style=flat-square" alt="Latest Version"></a>
    <a href="https://github.com/DirectoryTree/Anonymize/blob/master/LICENSE.md"><img src="https://img.shields.io/github/license/DirectoryTree/Anonymize?style=flat-square" alt="License"></a>
</p>

<p align="center">
    <a href="#installation">Installation</a>
    <span> · </span>
    <a href="#usage">Usage</a>
    <span> · </span>
    <a href="#testing">Testing</a>
</p>

---

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

Install the package with Composer:

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

### Anonymizing JSON Resources

You can also anonymize Laravel JSON resources by implementing the `Anonymizable` interface and using the `AnonymizedResource` trait:

```php
<?php

namespace App\Http\Resources;

use Faker\Generator;
use Illuminate\Http\Request;
use DirectoryTree\Anonymize\Anonymizable;
use DirectoryTree\Anonymize\AnonymizedResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource implements Anonymizable
{
    use AnonymizedResource;

    public function toArray(Request $request): array
    {
        return $this->toAnonymized([
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
    }

    public function getAnonymizedAttributes(Generator $faker): array
    {
        return [
            'name' => $faker->name(),
            'email' => $faker->safeEmail(),
            'phone' => $faker->phoneNumber(),
        ];
    }
}
```

When anonymization is enabled, the resource will automatically replace sensitive data in the JSON response:

```php
Anonymize::enable();

$user = User::find(1);

$resource = new UserResource($user);

// Returns anonymized data instead of original values.
$response = $resource->resolve();
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

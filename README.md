# Anonymize

[![Latest Version on Packagist](https://img.shields.io/packagist/v/directorytree/anonymize.svg?style=flat-square)](https://packagist.org/packages/directorytree/anonymize)
[![Total Downloads](https://img.shields.io/packagist/dt/directorytree/anonymize.svg?style=flat-square)](https://packagist.org/packages/directorytree/anonymize)

**Anonymize** is an elegant Laravel package that seamlessly replaces sensitive model data with realistic fake data using Faker. Perfect for development environments, demos, and data sharing scenarios where you need to protect user privacy while maintaining data structure and relationships.

## ✨ Features

- 🔒 **Privacy-First**: Automatically anonymize sensitive model attributes
- 🎯 **Consistent Data**: Same model ID always generates the same fake data
- ⚡ **Performance Optimized**: Intelligent caching prevents redundant fake data generation
- 🎛️ **Granular Control**: Enable/disable anonymization globally or per-model instance
- 🔄 **Seamless Integration**: Works transparently with existing Eloquent models
- 🛡️ **Serialization Safe**: Prevents data leaks even when models are serialized

## Installation

You can install the package via Composer:

```bash
composer require directorytree/anonymize
```

The package will automatically register its service provider.

## Quick Start

### 1. Set Up Your Model

Make your Eloquent model implement the `Anonymizable` contract and use the `Anonymized` trait:

```php
<?php

namespace App\Models;

use DirectoryTree\Anonymize\Anonymizable;
use DirectoryTree\Anonymize\Anonymized;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;

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

### 2. Enable Anonymization

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

## Usage

### Global Control

Control anonymization across your entire application:

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

### Per-Model Control

Temporarily disable anonymization for specific operations:

```php
$user = User::find(1);

// Get real data even when anonymization is globally enabled
$realData = $user->withoutAnonymization(function ($model) {
    return $model->attributesToArray();
});

// Or access individual attributes
$realName = $user->withoutAnonymization(fn ($model) => $model->name);
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

### Advanced Configuration

#### Custom Seed Generation

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

#### Conditional Anonymization

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

### Environment-Based Usage

Perfect for different environments:

```php
// In your AppServiceProvider or middleware
if (app()->environment(['local', 'staging'])) {
    Anonymize::enable();
}
```

### API Resources

Works seamlessly with API resources:

```php
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name, // Will be anonymized if enabled
            'email' => $this->email, // Will be anonymized if enabled
            'created_at' => $this->created_at,
        ];
    }
}
```

## How It Works

1. **Seeded Generation**: Each model generates a unique seed based on its class and ID
2. **Consistent Output**: The same seed always produces the same fake data
3. **Selective Replacement**: Only attributes defined in `getAnonymizedAttributes()` are replaced
4. **Transparent Operation**: Works with `attributesToArray()`, `toArray()`, `toJson()`, and direct attribute access
5. **Performance Optimized**: Fake data is cached per model instance to avoid regeneration

## Use Cases

- **Development Environments**: Work with realistic data without exposing sensitive information
- **Demo Applications**: Show your app with convincing data that's not real
- **Data Sharing**: Share database dumps with partners or team members safely
- **Testing**: Create consistent test scenarios with predictable fake data
- **Staging Environments**: Mirror production data structure without privacy concerns

## Performance

The package is designed for optimal performance:

- Fake data is generated only when needed
- Results are cached per model instance
- Cache is automatically invalidated when the model's seed changes
- Minimal overhead when anonymization is disabled

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

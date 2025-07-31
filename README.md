# Anonymize

A Laravel package for data anonymization using Faker.

## Installation

You can install the package via composer:

```bash
composer require directorytree/anonymize
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="anonymize-config"
```

This is the contents of the published config file:

```php
<?php

return [
    'default_faker_locale' => 'en_US',

    'fields' => [
        'email' => 'safeEmail',
        'first_name' => 'firstName',
        'last_name' => 'lastName',
        'name' => 'name',
        'phone' => 'phoneNumber',
        'address' => 'address',
        'city' => 'city',
        'postal_code' => 'postcode',
        'ip_address' => 'ipv4',
    ],
];
```

## Usage

### Basic Usage

```php
use DirectoryTree\Anonymize\AnonymizeManager;

$anonymizer = new AnonymizeManager();

// Anonymize a single field
$anonymizedEmail = $anonymizer->field('email', 'john@example.com');

// Anonymize multiple fields
$data = [
    'email' => 'john@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
];

$anonymizedData = $anonymizer->fields($data);
```

### Using the Facade

```php
use DirectoryTree\Anonymize\AnonymizeManager;

$anonymizedEmail = AnonymizeManager::field('email', 'john@example.com');
$anonymizedData = AnonymizeManager::fields($data);
```

### Custom Locale

```php
$anonymizer = new Anonymize('fr_FR');
```

### Direct Faker Access

```php
$faker = $anonymizer->faker();
$customValue = $faker->sentence;
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

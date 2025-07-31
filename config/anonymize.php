<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Anonymization Settings
    |--------------------------------------------------------------------------
    |
    | This configuration file contains the default settings for the
    | anonymize package. You can modify these settings to suit your needs.
    |
    */

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

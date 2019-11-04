Laravel Nova Passport
============

# Installation

add repository to composer under `repositories` array in your composer file

```json
{
    "type": "path",
    "url": "./packages//petecheyne/nova-passport"
}
```

Add ` "petecheyne/nova-passport": "*"` to composer `require` array

And run `composer update` and run `php artisan migrate`

# Add This field to user resource:

```php
    HasMany::make(__('Tokens'), 'tokens', Token::class),
```

Enjoy!

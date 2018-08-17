# ZF3 Country dropdown

Country dropdown for [zend-form](https://docs.zendframework.com/zend-form/) (Zend Framework 3).

Works with 2-letter ISO 3166 codes

Options are displayed in the current locale.

## Installation

```
$ composer require polderknowledge/country-dropdown
```

## Usage

in Form::init():

```php
$this->add([
    'type' => CountrySelect::class,
    'name' => 'country',
    'options' => [
        // optional countries to display first in the dropdown above a separator
        'top_country_codes' => ['NL', 'ES'],
    ],
]);
```

Rendering can be done in the same way as a normal select element.

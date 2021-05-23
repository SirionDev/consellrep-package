# siriondev/consellrep

## Installation

_This package can be used with Laravel 8.0 or higher._

Install the package via composer:

```bash
composer require siriondev/consellrep
```

The service provider will automatically get registered. You may manually add the service provider in your `config/app.php` file:

```php
'providers' => [
    // ...
    Siriondev\ConsellRepublica\Providers\ConsellRepublicaProvider::class,
];
```

### Config and Translations

You should publish the translations and the `config/cxr.php` config file with:

```bash
php artisan vendor:publish --tag="consellrep-config"
php artisan vendor:publish --tag="consellrep-translations"
```

### Migrations

You may also want to publish the migration to add the `idrepublicana` field into your users table:

```bash
php artisan vendor:publish --tag="consellrep-migrations"
```

### Clear your config cache

```bash
php artisan optimize
```

## Usage

### Validator

You can use the `idrepublicana` rule to check whether the user input is valid or not.

```php
public function rules()
{
    return [
        'id' => 'required|idrepublicana'
    ];
}
```

You can also set parameters to check whether the IDR is valid, active, underaged, or just check the format.

```php
public function rules()
{
    return [
        'id' => 'required|idrepublicana:active,valid,underaged,format'
    ];
}
```

### Facade

You can also use the IdentitatDigitalRepublicana Facade.
The `validate` method returns an object that can be used to check different attributes from the IDR.

```php
use Siriondev\ConsellRepublica\Facades\IdentitatDigitalRepublicana;

class Controller extends BaseController
{
    public function register(Request $request)
    {
        $idr = IdentitatDigitalRepublicana::validate($request->id);

        if ($idr->getStatus()) {    // Request OK

            $idr->isValid();        // IDR is valid

            $idr->isActive();       // IDR is active

            $idr->isUnderaged();    // IDR is underaged

            $idr->isFormat();       // IDR format correct (C-999-99999)

        } else {

            $idr->getMessage();     // Get the error message
        }
    }
}
```

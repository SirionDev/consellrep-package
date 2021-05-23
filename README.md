# Package - Identitat Digital Republiana

## Installation

_This package can be used with Laravel 8.0 or higher._

Install the package via composer:

```
composer require siriondev/consellrep
```

The service provider will automatically get registered. You may manually add the service provider in your `config/app.php` file:

```
'providers' => [
    // ...
    Siriondev\ConsellRepublica\Providers\ConsellRepublicaProvider::class,
];
```

You should publish the translations and the `config/cxr.php` config file with:

```
php artisan vendor:publish --tag="consellrep-config"
php artisan vendor:publish --tag="consellrep-translations"
```

You may also want to publish the migration to add the `idrepublicana` field into your users table:
```
php artisan vendor:publish --tag="consellrep-migrations"
```

Clear your config cache
```
php artisan optimize
```

## Usage

### Validator

You can use the `idrepublicana` rule to check whether the user input is valid or not.

```
public function rules()
{
    return [
        'id' => 'required|idrepublicana'
    ];
}
```

### Facade

You can also use the IdentitatDigitalRepublicana Facade.

```
use Siriondev\ConsellRepublica\Facades\IdentitatDigitalRepublicana;

class Controller extends BaseController
{
    public function register(Request $request)
    {
        // ...
        $bool = IdentitatDigitalRepublicana::validate($request->id);
    }
}
```

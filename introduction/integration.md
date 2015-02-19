## Integration

Cartalyst packages are framework agnostic and as such can be integrated easily natively or with your favorite framework.

### Laravel 5

The Alerts package has optional support for Laravel 5 and it comes bundled with a Service Provider and a Facade for easy integration.

After installing the package, open your Laravel config file located at `config/app.php` and add the following lines.

In the `$providers` array add the following service provider for this package.

```php
'Cartalyst\Alerts\Laravel\AlertsServiceProvider',
```

In the `$aliases` array add the following facades for this package.

```php
'Alert' => 'Cartalyst\Alerts\Laravel\Facades\Alert',
```

#### Configuration

After installing, you can publish the package configuration file into your application by running the following command on your terminal:

`php artisan vendor:publish`

This will publish the config file to `config/cartalyst.alerts.php` where you can modify the package configuration.

### Native

Alerts ships with a native facade that makes using it as simple as follows:

```php
// Import the necessary classes
use Cartalyst\Alerts\Native\Facades\Alert;

// Include the composer autoload file
require 'vendor/autoload.php';

// Start using alerts
Alert::error(['foo', 'bar']);
```

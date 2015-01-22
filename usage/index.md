## Usage

In this section we'll show how you can make us of the alerts package.

### Alert Types

Types can be decided by the user, the type is defined by the method name you call on the facade.

`error` type

```php
Alert::error('error message');
```

`warning` type

```php
Alert::warning('warning message');
```

`foo` type

```php
Alert::foo('foo message');
```

### Notifiers

Notifiers are responsible for storing the alerts across your application.

Alerts ships with two notifiers.

#### Flash Notifier (notifies on redirections)

The flash notifier requires `illuminate/session` and `illuminate/filesystem` in order to add flashing notifications capability.

The flash notifier will persist the alert for one request.

##### Usage

```php
Alert::error('Error message');
```

> **Note** The flash notifier is the default notifier, therefore, all alerts that are directly called on the facade will be handled by the flash notifier.

#### Notifier (notifies on the same request)

The notifier will persist the alerts during the current request only.

##### Usage

```php
Alert::view()->error('Error message');
```

### Retrieving alerts

#### Alert::all($type)

You can retrieve all alerts, or a specific type of alerts by passing in the type to the `all` method.

```php
// All alerts
$alerts = Alert::all();

// Alerts of the type `foo`
$alerts = Alert::all('foo');
```

#### Alert::except($type)

Using `except` you can retrieve all alerts, except a specific type. This comes in handy when you have a `form` type that you want to exclude from your general alerts view.

```php
// All except `form` alerts.
$alerts = Alert::except('fom');
```

### Generating the alerts view

```php
@foreach (Alert::all() as $alert)

<div class="alert">

	<p>{{ $alert->message }}</p>

</div>

@endforeach
```

> **Note** The example above uses Laravel's blade syntax for readability.

#### Class

Alert classes can be useful when it comes to adding classes to HTML elements, let's take an `error` alert as an example.

The `class` property on every alert defaults to the error type, in our case `error`.

##### Alert

```php
Alert::error('Error');
```

##### View

The following view will output the class `alert-error` on the `div` tag.

```
@foreach (Alert::all() as $alert)

<div class="alert alert-{{ $alert->class }}">

	<p>{{ $alert->message }}</p>

</div>

@endforeach
```

Changing that class to `alert-danger` can be achieved by:


- Native

Calling the setConfig method on the `AlertsBootstrapper` and passing in the $config array.

```php
$config = [

	'error' => 'danger',

];

Cartalyst\Alerts\Native\AlertsBootstrapper::setConfig($config);
```

- Laravel

Updating the config file and providing a key/value array that defines the classes to rewrite.

```php
return [

	'error' => 'danger',

];
```

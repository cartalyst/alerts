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

#### Default

The default notifier is used without having to call an additional method that returns the desired notifier.

- Laravel

The default notifier is set to `flash`.

- Native

The default notifier can be set using `Alert::setDefaultNotifier($notifier)`

#### Retrieve a notifier

Sending alerts using a different notifier requires retrieving the notifier first.

##### Usage

Assuming we have a `view` notifier we can the following.

```php
// Call the notifier `key` on the facade to retrieve the notifier.
Alert::view()->error('foo');

// Call the `notifier` method and pass in the notifier key.
Alert::notifier('view')->error('foo');
```

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

The methods below will apply the desired filters to the list of alerts, afterwards, `get` can be called in order to retrieve the alerts.

The methods below can be chained to filter down alerts more than once.

```php
Alert::whereType('foo')->whereArea('bar')->get();
```

#### `Alert::whereType($type|$types)`

This method will filter down alerts based on the given type(s).

##### Usage

```php
// Returns all alerts of type `foo`
Alert::whereType('foo')->get();

// Returns all alerts of type `foo` and `bar`
Alert::whereType([ 'foo', 'bar' ])->get();
```

#### `Alert::whereArea($area|$areas)`

This method will filter down alerts based on the given area(s).

##### Usage

```php
// Returns all alerts of area `foo`
Alert::whereArea('foo')->get();

// Returns all alerts of area `foo` and `bar`
Alert::whereArea([ 'foo', 'bar' ])->get();
```

#### `Alert::whereNotType($type|$types)`

This method will filter down alerts excluding the given type(s).

##### Usage

```php
// Returns all alerts excluding type `foo`
Alert::whereNotType('foo')->get();

// Returns all alerts excluding type `foo` and `bar`
Alert::whereNotType([ 'foo', 'bar' ])->get();
```

#### `Alert::whereNotArea($type|$types)`

This method will filter down alerts excluding the given area(s).

##### Usage

```php
// Returns all alerts excluding area `foo`
Alert::whereNotArea('foo')->get();

// Returns all alerts excluding area `foo` and `bar`
Alert::whereNotArea([ 'foo', 'bar' ])->get();
```

#### Alert::get()

You can retrieve all or the filtered list of alerts using the `get` method. Calling this method will return a list of alerts and reset any applied filters afterwards.

```php
// All alerts
$alerts = Alert::get();
```

### Generating the alerts view

```php
@foreach (Alert::get() as $alert)

<div class="alert">

	<p>{{ $alert->message }}</p>

</div>

@endforeach
```

> **Note** The example above uses Laravel's blade syntax for readability.

#### Class

Alert classes can be useful when it comes to adding classes to HTML elements, let's take an `error` alert as an example.

The `class` property on every alert defaults to the alert type, in our case `error`.

##### Alert

```php
Alert::error('Error');
```

##### View

The following view will output the class `alert-error` on the `div` tag.

```
@foreach (Alert::get() as $alert)

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

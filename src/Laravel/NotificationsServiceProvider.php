<?php namespace Cartalyst\Notifications\Laravel;
/**
 * Part of the Notifications package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the license.txt file.
 *
 * @package    Notifications
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	protected $defer = true;

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		$this->registerNotifications();
	}

	/**
	 * Register the Notifications.
	 *
	 * @return void
	 */
	protected function registerNotifications()
	{
		$this->app->bindShared('alert', function($app)
		{
			$notifier = $this->app->make('Cartalyst\Notifications\Notifier');
			$redirectionNotifier = $this->app->make('Cartalyst\Notifications\RedirectionNotifier');

			$notifications = $this->app->make('Cartalyst\Notifications\Notifications');

			$notifications->addNotifier('default', $notifier);
			$notifications->addNotifier('redirection', $redirectionNotifier);

			return $notifications;
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function provides()
	{
		return [
			'alert',
		];
	}

}

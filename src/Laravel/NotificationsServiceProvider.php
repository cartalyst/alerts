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
use Cartalyst\Notifications\Notifications;

class NotificationsServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	protected $defer = true;

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		$this->package('cartalyst/notifications', 'cartalyst/notifications', __DIR__.'/..');
	}

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
		$this->app['notifications'] = $this->app->share(function($app)
		{
			return new Notifications();
		});

		$this->app->alias('notifications', 'Cartalyst\Notifications\Notifications');
	}

	/**
	 * {@inheritDoc}
	 */
	public function provides()
	{
		return [
			'notifications',
		];
	}

}

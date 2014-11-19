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
 * @version    0.1.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Support\ServiceProvider;
use Cartalyst\Notifications\Storage\StorageInterface;
use Cartalyst\Notifications\Storage\IlluminateSession;

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
		$this->registerSession();

		$this->registerNotifications();
	}

	/**
	 * Register notifications.
	 *
	 * @return void
	 */
	protected function registerNotifications()
	{
		$this->app->bindShared('alert', function($app)
		{
			$config = $this->app['config']['cartalyst/notifications::config.classes'];
			$notifier      = $this->app->make('Cartalyst\Notifications\Notifier', [$config]);
			$flashNotifier = $this->app->make('Cartalyst\Notifications\FlashNotifier', [$config]);

			$notifications = $this->app->make('Cartalyst\Notifications\Notifications');

			$notifications->addNotifier('default', $notifier);
			$notifications->addNotifier('flash', $flashNotifier);

			return $notifications;
		});
	}

	/**
	 * Registers the session.
	 *
	 * @return void
	 */
	protected function registerSession()
	{
		$this->app['Cartalyst\Notifications\Storage\StorageInterface'] = $this->app->share(function($app)
		{
			return new IlluminateSession($app['session.store']);
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

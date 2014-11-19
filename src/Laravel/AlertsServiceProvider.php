<?php namespace Cartalyst\Alerts\Laravel;
/**
 * Part of the Alerts package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the license.txt file.
 *
 * @package    Alerts
 * @version    0.1.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Support\ServiceProvider;
use Cartalyst\Alerts\Storage\StorageInterface;
use Cartalyst\Alerts\Storage\IlluminateSession;

class AlertsServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	protected $defer = true;

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		$this->package('cartalyst/alerts', 'cartalyst/alerts', __DIR__.'/..');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		$this->registerSession();

		$this->registerAlerts();
	}

	/**
	 * Register alerts.
	 *
	 * @return void
	 */
	protected function registerAlerts()
	{
		$this->app->bindShared('alert', function($app)
		{
			$config = $this->app['config']['cartalyst/alerts::config.classes'];

			$notifier      = $this->app->make('Cartalyst\Alerts\Notifier', [$config]);
			$flashNotifier = $this->app->make('Cartalyst\Alerts\FlashNotifier', [$config]);

			$alerts = $this->app->make('Cartalyst\Alerts\Alerts');

			$alerts->addNotifier('default', $notifier);
			$alerts->addNotifier('flash', $flashNotifier);

			return $alerts;
		});
	}

	/**
	 * Registers the session.
	 *
	 * @return void
	 */
	protected function registerSession()
	{
		$this->app['Cartalyst\Alerts\Storage\StorageInterface'] = $this->app->share(function($app)
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

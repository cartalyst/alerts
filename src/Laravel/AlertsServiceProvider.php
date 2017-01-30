<?php

/**
 * Part of the Alerts package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Alerts
 * @version    2.0.2
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2017, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Alerts\Laravel;

use Illuminate\Support\ServiceProvider;
use Cartalyst\Alerts\Notifiers\Notifier;
use Cartalyst\Alerts\Notifiers\FlashNotifier;
use Cartalyst\Alerts\Storage\IlluminateSession;

class AlertsServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $defer = true;

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->prepareResources();

        $this->registerSession();

        $this->registerAlerts();
    }

    /**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../config/config.php');

        $this->mergeConfigFrom($config, 'cartalyst.alerts');

        $this->publishes([
            $config => config_path('cartalyst.alerts.php'),
        ], 'config');
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'alerts',
        ];
    }

    /**
     * Register alerts.
     *
     * @return void
     */
    protected function registerAlerts()
    {
        $this->app->singleton('alerts', function($app) {
            $config = $this->app['config']->get('cartalyst.alerts');

            $alerts = $this->app->make('Cartalyst\Alerts\Alerts');

            $viewNotifier  = new Notifier('view', $config['classes']);
            $flashNotifier = new FlashNotifier('flash', $config['classes'], $this->app['Cartalyst\Alerts\Storage\StorageInterface']);

            $alerts->addNotifier($viewNotifier);
            $alerts->addNotifier($flashNotifier);

            $alerts->setDefaultNotifier($config['default']);

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
        $this->app->singleton('Cartalyst\Alerts\Storage\StorageInterface', function ($app) {
            return new IlluminateSession($app['session.store']);
        });
    }
}

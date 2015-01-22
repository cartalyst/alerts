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
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Alerts\Laravel;

use Illuminate\Support\ServiceProvider;
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
        $this->app->bindShared('alerts', function ($app) {
            $config = $this->app['config']['cartalyst/alerts::config'];

            $alerts = $this->app->make('Cartalyst\Alerts\Alerts');

            $classes = array_get($config, 'classes');

            $alerts->addNotifier(
                $this->app->make('Cartalyst\Alerts\Notifiers\Notifier', ['view', $classes])
            );

            $alerts->addNotifier(
                $this->app->make('Cartalyst\Alerts\Notifiers\FlashNotifier', ['flash', $classes])
            );

            $alerts->setDefaultNotifier(array_get($config, 'default'));

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
        $this->app['Cartalyst\Alerts\Storage\StorageInterface'] = $this->app->share(function ($app) {
            return new IlluminateSession($app['session.store']);
        });
    }
}

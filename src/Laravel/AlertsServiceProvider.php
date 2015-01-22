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
use Cartalyst\Alerts\Storage\StorageInterface;
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
     * Register alerts.
     *
     * @return void
     */
    protected function registerAlerts()
    {
        $this->app->bindShared('alerts', function ($app) {
            $config = $this->app['config']['cartalyst/alerts::config'];

            $notifier      = $this->app->make('Cartalyst\Alerts\Notifier', [array_get($config, 'classes')]);
            $flashNotifier = $this->app->make('Cartalyst\Alerts\FlashNotifier', [array_get($config, 'classes')]);

            $alerts = $this->app->make('Cartalyst\Alerts\Alerts');

            $alerts->setDefaultNotifier(array_get($config, 'default'));

            $alerts->addNotifier('flash', $flashNotifier);
            $alerts->addNotifier('view', $notifier);

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

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'alerts',
        ];
    }
}

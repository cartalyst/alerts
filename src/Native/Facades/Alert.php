<?php

/*
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
 * @version    8.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2024, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Cartalyst\Alerts\Native\Facades;

use Cartalyst\Alerts\Alerts;
use Cartalyst\Alerts\Native\AlertsBootstrapper;

class Alert
{
    /**
     * The alerts instance.
     *
     * @var \Cartalyst\Alerts\Alerts
     */
    protected $alerts;

    /**
     * The Native Bootstraper instance.
     *
     * @var \Cartalyst\Alerts\Native\AlertsBootstrapper
     */
    protected static $instance;

    /**
     * Constructor.
     *
     * @param \Cartalyst\Alerts\Native\AlertsBootstrapper|null $bootstrapper
     *
     * @return void
     */
    public function __construct(AlertsBootstrapper $bootstrapper = null)
    {
        if ($bootstrapper === null) {
            $bootstrapper = new AlertsBootstrapper();
        }

        $this->alerts = $bootstrapper->createAlerts();
    }

    /**
     * Returns the Alerts instance.
     *
     * @return \Cartalyst\Alerts\Alerts
     */
    public function getAlerts(): Alerts
    {
        return $this->alerts;
    }

    /**
     * Creates a new Native Bootstraper instance.
     *
     * @param \Cartalyst\Alerts\Native\AlertsBootstrapper $bootstrapper
     *
     * @return \Cartalyst\Alerts\Native\Facades\Alert
     */
    public static function instance(AlertsBootstrapper $bootstrapper = null)
    {
        if (static::$instance === null) {
            static::$instance = new static($bootstrapper);
        }

        return static::$instance;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args)
    {
        $instance = static::instance()->getAlerts();

        return call_user_func_array([$instance, $method], $args);
    }
}

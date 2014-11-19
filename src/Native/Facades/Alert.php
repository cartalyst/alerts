<?php namespace Cartalyst\Alerts\Native\Facades;
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

use Cartalyst\Alerts\Native\AlertsBootstrapper;

class Alert {

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
	 * @param  \Cartalyst\Alerts\Native\AlertsBootstrapper  $bootstrapper
	 * @return void
	 */
	public function __construct(AlertsBootstrapper $bootstrapper = null)
	{
		if ($bootstrapper === null)
		{
			$bootstrapper = new AlertsBootstrapper;
		}

		$this->alerts = $bootstrapper->createAlerts();
	}

	/**
	 * Returns the Alerts instance.
	 *
	 * @return \Cartalyst\Alerts\Alerts
	 */
	public function getAlerts()
	{
		return $this->alerts;
	}

	/**
	 * Creates a new Native Bootstraper instance.
	 *
	 * @param  \Cartalyst\Alerts\Native\AlertsBootstrapper  $bootstrapper
	 * @return void
	 */
	public static function instance(AlertsBootstrapper $bootstrapper = null)
	{
		if (static::$instance === null)
		{
			static::$instance = new static($bootstrapper);
		}

		return static::$instance;
	}

	/**
	 * Handle dynamic, static calls to the object.
	 *
	 * @param  string  $method
	 * @param  array  $args
	 * @return mixed
	 */
	public static function __callStatic($method, $args)
	{
		$instance = static::instance()->getAlerts();

		return call_user_func_array([$instance, $method], $args);
	}

}

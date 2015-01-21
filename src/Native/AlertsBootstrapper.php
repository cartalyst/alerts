<?php namespace Cartalyst\Alerts\Native;
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
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Session\Store;
use Illuminate\Filesystem\Filesystem;
use Cartalyst\Alerts\Notifier;
use Illuminate\Session\FileSessionHandler;
use Cartalyst\Alerts\Alerts;
use Cartalyst\Alerts\FlashNotifier;
use Cartalyst\Alerts\Storage\NativeSession;

class AlertsBootstrapper {

	/**
	 * Configuration array.
	 *
	 * @var array
	 */
	protected static $config = [];

	/**
	 * Creates a sentinel instance.
	 *
	 * @return \Cartalyst\Alerts\Alerts
	 */
	public function createAlerts()
	{
		$alerts = new Alerts();

		$this->createNotifier($alerts);
		$this->createFlashNotifier($alerts);

		return $alerts;
	}

	/**
	 * Sets the configuration array.
	 *
	 * @param  array  $config
	 * @return void
	 */
	public static function setConfig(array $config)
	{
		static::$config = $config;
	}

	/**
	 * Returns the configuration array.
	 *
	 * @return array
	 */
	public static function getConfig()
	{
		return static::$config;
	}

	/**
	 * Creates and adds a new notifier.
	 *
	 * @param  \Cartalyst\Alerts\Alerts  $alerts
	 * @return void
	 */
	protected function createNotifier($alerts)
	{
		$notifier = new Notifier(static::$config);
		$alerts->addNotifier('default', $notifier);
	}

	/**
	 * Creates and adds a new flash notifier.
	 *
	 * @param  \Cartalyst\Alerts\Alerts  $alerts
	 * @return void
	 */
	protected function createFlashNotifier($alerts)
	{
		if ($session = $this->createSession())
		{
			$flashNotifier = new FlashNotifier(static::$config, $session);
			$alerts->addNotifier('flash', $flashNotifier);
		}
	}

	/**
	 * Creates a session instance.
	 *
	 * @return \Cartalyst\Alerts\Storage\StorageInterface|null
	 */
	protected function createSession()
	{
		if (class_exists('Illuminate\Filesystem\Filesystem') && class_exists('Illuminate\Session\FileSessionHandler'))
		{
			$fileSessionHandler = new FileSessionHandler(new Filesystem(), __DIR__.'/../../../../../storage/sessions');

			$store = new Store('foo', $fileSessionHandler);

			return new NativeSession($store);
		}
	}

}

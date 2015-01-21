<?php namespace Cartalyst\Alerts\Storage;
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

use Illuminate\Cookie\CookieJar;
use Illuminate\Session\Store as SessionStore;

class NativeSession extends IlluminateSession implements StorageInterface {

	/**
	 * Creates a new Native Session driver for Alerts.
	 *
	 * @param  \Illuminate\Session\Store  $session
	 * @param  string  $instance
	 * @param  string  $key
	 * @param  array  $config
	 * @return void
	 */
	public function __construct(SessionStore $session, $instance = null, $key = null, $config = [])
	{
		parent::__construct($session, $instance, $key);

		// Cookie configuration
		$lifetime = array_get($config, 'lifetime', 120);
		$path     = array_get($config, 'path', '/');
		$domain   = array_get($config, 'domain', null);
		$secure   = array_get($config, 'secure', false);
		$httpOnly = array_get($config, 'httpOnly', true);

		if ($cookieId = array_get($_COOKIE, $session->getName()))
		{
			$session->setId($cookieId);

			$session->setName($cookieId);
		}

		$cookie = with(new CookieJar)->make($session->getName(), $session->getId(), $lifetime, $path, $domain, $secure, $httpOnly);

		setcookie($cookie->getName(), $cookie->getValue(), $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());

		$session->start();
	}

	/**
	 * Called upon destruction of the native session handler.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		$this->session->save();
	}

}

<?php namespace Cartalyst\Notifications\Storage;
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

use Illuminate\Session\Store as SessionStore;

class IlluminateSession implements StorageInterface {

	/**
	 * Session store object.
	 *
	 * @var \Illuminate\Session\Store
	 */
	protected $session;

	/**
	 * Creates a new Illuminate based Session driver for Notifications.
	 *
	 * @param  \Illuminate\Session\Store  $session
	 * @param  string  $key
	 * @return void
	 */
	public function __construct(SessionStore $session)
	{
		$this->session = $session;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get($key)
	{
		return $this->session->get($key);
	}

	/**
	 * {@inheritDoc}
	 */
	public function flash($key, $value)
	{
		return $this->session->flash($key, $value);
	}

}

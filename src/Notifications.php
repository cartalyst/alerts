<?php namespace Cartalyst\Notifications;
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
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Session\Store;

class Notifications {

	/**
	 * @var \Illuminate\Session\Store
	 */
	protected $session;

	/**
	 * Constructor.
	 *
	 * @param  Store  $session
	 * @return void
	 */
	public function __construct(Store $session)
	{
		$this->session = $session;
	}

	public function notify($message, $type = 'info', $preserve = false)
	{
		$messages = $this->session->get("notifications") ?: [];

		$messages[$type][] = $message;

		$this->session->flash("notifications", $messages);
	}

	public function flush($type)
	{
		$messages = $this->session->get("notifications") ?: [];

		unset($messages[$type]);

		$this->session->flash("notifications", $messages);
	}

	protected function multiple($messages, $type)
	{
		$this->flush($type);

		if ( ! is_array($messages))
		{
			$messages = [$messages];
		}

		foreach ($messages as $key => $message)
		{
			$this->notify($message, $type, true);
		}
	}

	public function __call($method, $arguments)
	{
		if (starts_with($method, 'flush'))
		{
			$type = strtolower(substr($method, 5));

			return $this->flush($type);
		}

		return $this->multiple(head($arguments), $method);
	}

}

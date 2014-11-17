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

use Cartalyst\Notifications\View\Factory;

class Notifier implements NotifierInterface {

	/**
	 * Notifications.
	 *
	 * @var array
	 */
	protected $notifications = [];

	/**
	 * Flashes notifications.
	 *
	 * @param  array|string  $messages
	 * @param  string  $type
	 * @return void
	 */
	public function notify($messages, $type)
	{
		$this->remove($type);

		if ( ! is_array($messages))
		{
			$messages = [$messages];
		}

		foreach ($messages as $message)
		{
			$this->notifications[$type][] = $message;
		}
	}

	/**
	 * Returns all notifications.
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->notifications;
	}

	/**
	 * Removes the given type from messages.
	 *
	 * @param  string  $type
	 * @return void
	 */
	protected function remove($type)
	{
		unset($this->notifications[$type]);
	}

	/**
	 * Dynamically passes notifications to the view.
	 *
	 * @param  string  $method
	 * @param  array  $parameters
	 * @return void
	 */
	public function __call($method, $parameters)
	{
		return $this->notify($parameters[0], $method);
	}

}

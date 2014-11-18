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

class Notifier implements NotifierInterface {

	/**
	 * Notifications.
	 *
	 * @var array
	 */
	protected $notifications = [];

	/**
	 * {@inheritDoc}
	 */
	public function notify($messages, $type, $area = 'default', $isFlash = false, $extra = null)
	{
		$this->remove($type);

		if ( ! is_array($messages))
		{
			$messages = [$messages];
		}

		foreach ($messages as $message)
		{
			$this->notifications[] = new Message($message, $type, $area, $isFlash, $extra);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function get()
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
		list($type, $area, $extra) = $this->parseParameters($parameters);

		return $this->notify($type, $method, $area, $extra);
	}

	/**
	 * Parses parameters.
	 *
	 * @param  array  $parameters
	 * @return array
	 */
	protected function parseParameters($parameters)
	{
		$type = array_get($parameters, 0);
		$area = array_get($parameters, 1, 'default');
		$extra = array_get($parameters, 2);

		return [$type, $area, $extra];
	}

}

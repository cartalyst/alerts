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

use Illuminate\Container\Container;

class Notifications {

	/**
	 * Notifiers.
	 *
	 * @var array
	 */
	protected $notifiers = [];

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $container
	 * @return void
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Adds the given notifier.
	 *
	 * @param  string  $type
	 * @param  \Cartalyst\Notifications\NotifierInterface $notifier
	 * @return void
	 */
	public function addNotifier($type, NotifierInterface $notifier)
	{
		$this->notifiers[$type] = $notifier;
	}

	/**
	 * Removes the given type from notifiers.
	 *
	 * @param  string  $type
	 * @return void
	 */
	public function removeNotifier($type)
	{
		unset($this->notifiers[$type]);
	}

	/**
	 * Returns all notifications.
	 *
	 * @return array
	 */
	public function all($type = null)
	{
		$messages = [];

		$notifiers = $type ? [array_get($this->notifiers, $type)] : $this->notifiers;
		$notifiers = array_filter($notifiers);

		foreach ($notifiers as $notifier)
		{
			$messages = array_merge_recursive($messages, $notifier->all());
		}

		return $messages;
	}

	/**
	 * Dynamically resolve notifiers from the IoC container.
	 *
	 * @param  string  $method
	 * @param  array  $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if (isset($this->notifiers[$method]))
		{
			if (isset($parameters[0]))
			{
				$this->notifiers[$method]->setType($parameters[0]);
			}

			return $this->notifiers[$method];
		}

		if ( ! $parameters)
		{
			return $this->notifiers[$method] = $this->container->make(get_class($this->notifiers['default']));
		}

		return $this->notifiers['default']->notify($parameters[0], $method);
	}

}

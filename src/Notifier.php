<?php namespace Cartalyst\Alerts;
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

use Illuminate\Support\MessageBag;

class Notifier implements NotifierInterface {

	/**
	 * Configuration array.
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Constructor.
	 *
	 * @param  array  $config
	 * @return void
	 */
	public function __construct(array $config = [])
	{
		$this->config = $config;
	}

	/**
	 * Alerts.
	 *
	 * @var array
	 */
	protected $alerts = [];

	/**
	 * {@inheritDoc}
	 */
	public function alert($messages, $type, $area = 'default', $isFlash = false, $class = null)
	{
		$this->remove($type);

		if ( ! is_array($messages))
		{
			$messages = [$messages];
		}

		foreach ($messages as $message)
		{
			if ($message instanceof MessageBag)
			{
				foreach ($message->toArray() as $key => $value)
				{
					$this->alerts[] = new Message($key, head($value), $type, $area, $isFlash, $class);
				}
			}
			else
			{
				$this->alerts[] = new Message($message, $type, $area, $isFlash, $class);
			}
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function all()
	{
		return $this->alerts;
	}

	/**
	 * Removes the given type from messages.
	 *
	 * @param  string  $type
	 * @return void
	 */
	protected function remove($type)
	{
		unset($this->alerts[$type]);
	}

	/**
	 * Dynamically passes alerts to the view.
	 *
	 * @param  string  $method
	 * @param  array  $parameters
	 * @return void
	 */
	public function __call($method, $parameters)
	{
		list($message, $area) = $this->parseParameters($parameters);

		$class = array_get($this->config, $method, $method);

		return $this->alert($message, $method, $area, false, $class);
	}

	/**
	 * Parses parameters.
	 *
	 * @param  array  $parameters
	 * @return array
	 */
	protected function parseParameters($parameters)
	{
		$message = array_get($parameters, 0);
		$area = array_get($parameters, 1, 'default');

		return [$message, $area];
	}

}

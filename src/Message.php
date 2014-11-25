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

class Message {

	/**
	 * Message.
	 *
	 * @var string
	 */
	public $message;

	/**
	 * Message type.
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Message area.
	 *
	 * @var string
	 */
	public $area;

	/**
	 * Indicates whether the message is a flash message.
	 *
	 * @var bool
	 */
	public $isFlash;

	/**
	 * Message class.
	 *
	 * @var string
	 */
	public $class;

	/**
	 * The message identifier.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Constructor.
	 *
	 * @param  string  $key
	 * @param  string  $message
	 * @param  string  $type
	 * @param  string  $area
	 * @param  bool  $isFlash
	 * @return void
	 */
	public function __construct($message, $type, $area = 'default', $isFlash = false, $class = null, $key = null)
	{
		$this->message = $message;
		$this->type    = $type;
		$this->area    = $area;
		$this->isFlash = $isFlash;
		$this->class   = $class;
		$this->key     = $key;
	}

	/**
	 * Returns the message identifier.
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

}

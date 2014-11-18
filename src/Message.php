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

class Message {

	/**
	 * Indicates whether the message is a flash message.
	 *
	 * @var bool
	 */
	public $isFlash;

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
	 * Message extras.
	 *
	 * @var string
	 */
	public $extra;

	/**
	 * Message.
	 *
	 * @var string
	 */
	public $message;

	/**
	 * Constructor.
	 *
	 * @param  string  $message
	 * @param  string  $type
	 * @param  string  $area
	 * @param  bool  $isFlash
	 * @param  string  $extra
	 * @return void
	 */
	public function __construct($message, $type, $area = 'default', $isFlash = false, $extra = null)
	{
		$this->isFlash = $isFlash;
		$this->type    = $type;
		$this->area    = $area;
		$this->extra   = $extra;
		$this->message = $message;
	}

}

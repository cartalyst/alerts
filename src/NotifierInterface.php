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

interface NotifierInterface {

	/**
	 * Sends notifications.
	 *
	 * @param  array|string  $messages
	 * @param  string  $type
	 * @return void
	 */
	public function notify($messages, $type);

	/**
	 * Returns all notifications.
	 *
	 * @return array
	 */
	public function all();

}

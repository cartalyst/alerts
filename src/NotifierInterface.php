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
 * @version    0.1.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Cartalyst\Notifications\View\Factory;

interface NotifierInterface {

	/**
	 * Flashes notifications.
	 *
	 * @param  array|string  $messages
	 * @param  string  $type
	 * @param  string  $area
	 * @param  bool  $isFlash
	 * @param  string  $extra
	 * @return void
	 */
	public function notify($messages, $type, $area = 'default', $isFlash = false, $extra = null);

	/**
	 * Returns all notifications.
	 *
	 * @return array
	 */
	public function get();

}

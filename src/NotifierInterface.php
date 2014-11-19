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

use Cartalyst\Alerts\View\Factory;

interface NotifierInterface {

	/**
	 * Flashes alerts.
	 *
	 * @param  array|string  $messages
	 * @param  string  $type
	 * @param  string  $area
	 * @param  bool  $isFlash
	 * @param  string  $extra
	 * @return void
	 */
	public function alert($messages, $type, $area = 'default', $isFlash = false, $extra = null);

	/**
	 * Returns all alerts.
	 *
	 * @return array
	 */
	public function get();

}

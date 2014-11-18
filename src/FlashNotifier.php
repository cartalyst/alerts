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

class FlashNotifier extends Notifier {

	/**
	 * Session instance.
	 *
	 * @var \Illuminate\Session\Store
	 */
	protected $session;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Session\Store  $session
	 * @return void
	 */
	public function __construct(Store $session)
	{
		$this->session = $session;
	}

	/**
	 * {@inheritDoc}
	 */
	public function notify($messages, $type, $area = 'default', $isFlash = false, $extra = null)
	{
		parent::notify($messages, $type, $area, $isFlash, $extra);

		$this->commit();
	}

	/**
	 * {@inheritDoc}
	 */
	public function get()
	{
		return $this->session->get('cartalyst.notifications') ?: [];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function commit()
	{
		$this->session->flash('cartalyst.notifications', $this->notifications);
	}

}

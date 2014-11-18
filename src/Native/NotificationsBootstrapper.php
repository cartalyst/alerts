<?php namespace Cartalyst\Notifications\Native;
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
use Illuminate\Filesystem\Filesystem;
use Cartalyst\Notifications\Notifier;
use Illuminate\Session\FileSessionHandler;
use Cartalyst\Notifications\Notifications;
use Cartalyst\Notifications\FlashNotifier;
use Cartalyst\Notifications\Storage\NativeSession;

class NotificationsBootstrapper {

	/**
	 * Creates a sentinel instance.
	 *
	 * @return \Cartalyst\Notifications\Notifications
	 */
	public function createNotifications()
	{
		$notifier = $this->createNotifier();

		$notifications = new Notifications();

		$notifications->addNotifier('default', $notifier);

		if ($session = $this->createSession())
		{
			$flashNotifier = new FlashNotifier($session);
			$notifications->addNotifier('flash', $flashNotifier);
		}

		return $notifications;
	}

	/**
	 * Creates a new notifier.
	 *
	 * @return \Cartalyst\Notifications\NotifierInterface
	 */
	protected function createNotifier()
	{
		return new Notifier();
	}

	/**
	 * Creates a session instance.
	 *
	 * @return \Cartalyst\Notifications\Storage\StorageInterface|null
	 */
	protected function createSession()
	{
		if (class_exists('Illuminate\Filesystem\Filesystem') && class_exists('Illuminate\Session\FileSessionHandler'))
		{
			$fileSessionHandler = new FileSessionHandler(new Filesystem(), __DIR__.'/../../../../../storage/sessions');

			$store = new Store('foo', $fileSessionHandler);

			return new NativeSession($store);
		}
	}

}

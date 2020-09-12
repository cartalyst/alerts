<?php

/*
 * Part of the Alerts package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Alerts
 * @version    5.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2020, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Cartalyst\Alerts\Native;

use Cartalyst\Alerts\Alerts;
use Illuminate\Session\Store;
use Illuminate\Filesystem\Filesystem;
use Cartalyst\Alerts\Notifiers\Notifier;
use Illuminate\Session\FileSessionHandler;
use Cartalyst\Alerts\Storage\NativeSession;
use Cartalyst\Alerts\Notifiers\FlashNotifier;
use Cartalyst\Alerts\Storage\StorageInterface;

class AlertsBootstrapper
{
    /**
     * Configuration array.
     *
     * @var array
     */
    protected static $config = [];

    /**
     * Creates a sentinel instance.
     *
     * @return \Cartalyst\Alerts\Alerts
     */
    public function createAlerts(): Alerts
    {
        $alerts = new Alerts();

        $this->createNotifier($alerts);

        $this->createFlashNotifier($alerts);

        $alerts->setDefaultNotifier(static::$config['default'] ?? 'flash');

        return $alerts;
    }

    /**
     * Sets the configuration array.
     *
     * @param array $config
     *
     * @return void
     */
    public static function setConfig(array $config): void
    {
        static::$config = $config;
    }

    /**
     * Returns the configuration array.
     *
     * @return array
     */
    public static function getConfig(): array
    {
        return static::$config;
    }

    /**
     * Creates and adds a new notifier.
     *
     * @param \Cartalyst\Alerts\Alerts $alerts
     *
     * @return void
     */
    protected function createNotifier(Alerts $alerts): void
    {
        $alerts->addNotifier(
            new Notifier('view', static::$config)
        );
    }

    /**
     * Creates and adds a new flash notifier.
     *
     * @param \Cartalyst\Alerts\Alerts $alerts
     *
     * @return void
     */
    protected function createFlashNotifier(Alerts $alerts): void
    {
        if ($session = $this->createSession()) {
            $alerts->addNotifier(
                new FlashNotifier('flash', static::$config, $session)
            );
        }
    }

    /**
     * Creates a session instance.
     *
     * @return \Cartalyst\Alerts\Storage\StorageInterface|null
     */
    protected function createSession(): ?StorageInterface
    {
        if (class_exists(Filesystem::class) && class_exists(FileSessionHandler::class)) {
            $fileSessionHandler = new FileSessionHandler(new Filesystem(), __DIR__.'/../../../../../storage/sessions', 5);

            $store = new Store('alerts', $fileSessionHandler);

            return new NativeSession($store);
        }

        return null;
    }
}

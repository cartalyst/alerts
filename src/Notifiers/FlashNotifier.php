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

namespace Cartalyst\Alerts\Notifiers;

use Cartalyst\Alerts\Storage\StorageInterface;

class FlashNotifier extends Notifier
{
    /**
     * Session instance.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param string                                     $name
     * @param array                                      $config
     * @param \Cartalyst\Alerts\Storage\StorageInterface $session
     *
     * @return void
     */
    public function __construct(string $name, array $config, StorageInterface $session)
    {
        parent::__construct($name, $config);

        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function alert($messages, string $type, string $area = 'default', bool $isFlash = true, ?string $extra = null): void
    {
        parent::alert($messages, $type, $area, true, $extra);

        $this->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        return (array) $this->session->get('cartalyst.alerts', []);
    }

    /**
     * {@inheritdoc}
     */
    protected function commit(): void
    {
        $this->session->flash('cartalyst.alerts', $this->alerts);
    }
}

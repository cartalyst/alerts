<?php

/**
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
 * @version    2.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
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
     * @param  string  $name
     * @param  array  $config
     * @param  \Cartalyst\Alerts\Storage\StorageInterface  $session
     * @return void
     */
    public function __construct($name, array $config = [], StorageInterface $session)
    {
        parent::__construct($name, $config);

        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function alert($messages, $type, $area = 'default', $isFlash = true, $extra = null)
    {
        parent::alert($messages, $type, $area, true, $extra);

        $this->commit();
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->session->get('cartalyst.alerts', []);
    }

    /**
     * {@inheritDoc}
     */
    protected function commit()
    {
        $this->session->flash('cartalyst.alerts', $this->alerts);
    }
}

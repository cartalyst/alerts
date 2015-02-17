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

interface NotifierInterface
{
    /**
     * Returns the notifier name.
     *
     * @return string
     */
    public function getName();

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

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
 * @version    6.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2022, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Cartalyst\Alerts\Notifiers;

interface NotifierInterface
{
    /**
     * Returns the notifier name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Flashes alerts.
     *
     * @param array|string $messages
     * @param string       $type
     * @param string       $area
     * @param bool         $isFlash
     * @param string|null  $extra
     *
     * @return void
     */
    public function alert($messages, string $type, string $area = 'default', bool $isFlash = false, ?string $extra = null): void;

    /**
     * Returns all alerts.
     *
     * @return array
     */
    public function get(): array;
}

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

namespace Cartalyst\Alerts\Storage;

interface StorageInterface
{
    /**
     * Get the value from the storage.
     *
     * @param  string  $key
     * @param  string  $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Put a value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function flash($key, $value);
}

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

return [

    /*
    |--------------------------------------------------------------------------
    | Default Notifier
    |--------------------------------------------------------------------------
    |
    | The default notifier will be used out of the box without having to call
    | an additional method to determine which notifier is the default.
    |
    | 'default' => 'flash'
    |
    | Alerts ships with two notifiers, `flash`, `view`
    |
    */

    'default' => 'flash',

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    |
    | The class property on alerts defaults to the alert type by default.
    | To override classes of a specific alert type, specify it here as follows:
    |
    | 'error' => 'danger'
    |
    | This will set the class property on all error alerts to danger.
    |
    */

    'classes' => [

    ],

];

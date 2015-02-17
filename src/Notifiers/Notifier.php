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

use Cartalyst\Alerts\Message;
use Illuminate\Support\MessageBag;

class Notifier implements NotifierInterface
{
    /**
     * The notifier name.
     *
     * @var string
     */
    protected $name;

    /**
     * Configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Alerts.
     *
     * @var array
     */
    protected $alerts = [];

    /**
     * Constructor.
     *
     * @param  string  $name
     * @param  array  $config
     * @return void
     */
    public function __construct($name, array $config = [])
    {
        $this->name = $name;

        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function alert($messages, $type, $area = 'default', $isFlash = false, $class = null)
    {
        $this->remove($type);

        if ( ! is_array($messages)) {
            $messages = [$messages];
        }

        foreach ($messages as $message) {
            if ($message instanceof MessageBag) {
                foreach ($message->toArray() as $key => $value) {
                    $this->alerts[] = new Message(head($value), $type, $area, $isFlash, $class, $key);
                }
            } else {
                $this->alerts[] = new Message($message, $type, $area, $isFlash, $class);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->alerts;
    }

    /**
     * Dynamically passes alerts to the view.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return void
     */
    public function __call($method, $parameters)
    {
        list($message, $area) = $this->parseParameters($parameters);

        $class = isset($this->config[$method]) ? $this->config[$method] : $method;

        return $this->alert($message, $method, $area, false, $class);
    }

    /**
     * Removes the given type from messages.
     *
     * @param  string  $type
     * @return void
     */
    protected function remove($type)
    {
        unset($this->alerts[$type]);
    }

    /**
     * Parses parameters.
     *
     * @param  array  $parameters
     * @return array
     */
    protected function parseParameters($parameters)
    {
        $message = isset($parameters[0]) ? $parameters[0] : null;

        $area = isset($parameters[1]) ? $parameters[1] : 'default';

        return [ $message, $area ];
    }
}

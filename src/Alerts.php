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
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Alerts;

class Alerts
{
    /**
     * Notifiers.
     *
     * @var array
     */
    protected $notifiers = [];

    /**
     * Adds the given notifier.
     *
     * @param  string  $type
     * @param  \Cartalyst\Alerts\NotifierInterface  $notifier
     * @return void
     */
    public function addNotifier($type, NotifierInterface $notifier)
    {
        $this->notifiers[$type] = $notifier;
    }

    /**
     * Removes the given type from notifiers.
     *
     * @param  string  $type
     * @return void
     */
    public function removeNotifier($type)
    {
        unset($this->notifiers[$type]);
    }

    /**
     * Returns all or the given areas of alerts.
     *
     * @param  array|string  $areas
     * @return array
     */
    public function all($areas = null, $types = null)
    {
        return $this->filter($areas, $types);
    }

    /**
     * Returns all except the given types of alerts.
     *
     * @param  array|string  $areas
     * @param  array|string  $types
     * @return array
     */
    public function except($areas, $types = [])
    {
        return $this->filter($areas, $types, true);
    }

    /**
     * Returns form element errors.
     *
     * @param  string  $key
     * @param  string  $alert
     * @return string|null
     */
    public function form($key, $alert = null)
    {
        $messages = $this->all('form') ?: [];

        foreach ($messages as $message) {
            if ($message->getKey() === $key) {
                return $alert ?: $message->message;
            }
        }
    }

    /**
     * Returns the view notifier.
     *
     * @return \Cartalyst\Alerts\FlashNotifier
     */
    public function view()
    {
        return $this->notifiers['view'];
    }

    /**
     * Dynamically forward alerts.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->notifiers['default'], '__call'], [$method, $parameters]);
    }

    /**
     * Returns all or the given filtered alerts.
     *
     * @param  array|string  $areas
     * @param  array|string  $types
     * @param  bool  $exclude
     * @return array
     */
    protected function filter($areas = null, $types = null, $exclude = false)
    {
        if ( ! is_array($areas)) {
            $areas = (array) $areas;
        }

        if ( ! is_array($types)) {
            $types = (array) $types;
        }

        $messages = [];

        foreach ($this->notifiers as $notifier) {
            $messages = array_merge_recursive($messages, $notifier->all());
        }

        if ($areas) {
            if ($exclude) {
                foreach ($areas as $area) {
                    $messages = array_filter($messages, function ($message) use ($area) {
                        return $message->area !== $area;
                    });
                }
            } else {
                $messages = array_filter($messages, function ($message) use ($areas) {
                    return in_array($message->area, $areas);
                });
            }
        }

        if ($types) {
            if ($exclude) {
                foreach ($types as $type) {
                    $messages = array_filter($messages, function ($message) use ($type) {
                        return $message->type !== $type;
                    });
                }
            } else {
                $messages = array_filter($messages, function ($message) use ($types) {
                    return in_array($message->type, $types);
                });
            }
        }

        return $messages;
    }
}

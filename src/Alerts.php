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
     * Filters.
     *
     * @var array
     */
    protected $default;

    /**
     * Notifiers.
     *
     * @var array
     */
    protected $notifiers = [];

    /**
     * Filtered alerts.
     *
     * @var array
     */
    protected $filteredAlerts = [];

    /**
     * Filters.
     *
     * @var array
     */
    protected $filters = [];

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
     * Returns the filtered alerts.
     *
     * @return array
     */
    public function get()
    {
        // Retrieve all alerts if no filters are assigned
        if ( ! $this->filters) {
            $this->filter();
        }

        $filteredAlerts = $this->filteredAlerts;

        // Clear filters and filtered alerts
        $this->filters = [];
        $this->filteredAlerts = [];

        return $filteredAlerts;
    }

    /**
     * Filters alerts based on the given areas.
     *
     * @param  string|array  $areas
     * @return self
     */
    public function whereArea($areas)
    {
        $this->filter($areas);

        return $this;
    }

    /**
     * Filters alerts based on the given types.
     *
     * @param  string|array  $types
     * @return self
     */
    public function whereType($types)
    {
        $this->filter(null, $types);

        return $this;
    }

    /**
     * Filters alerts excluding the given areas.
     *
     * @param  string|array  $areas
     * @return self
     */
    public function whereNotArea($areas)
    {
        $this->filter($areas, null, true);

        return $this;
    }

    /**
     * Filters alerts excluding the given types.
     *
     * @param  string|array  $types
     * @return self
     */
    public function whereNotType($types)
    {
        $this->filter(null, $types, true);

        return $this;
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
        $messages = $this->whereArea('form')->get() ?: [];

        foreach ($messages as $message) {
            if ($message->getKey() === $key) {
                return $alert ?: $message->message;
            }
        }
    }

    /**
     * Returns the given notifier.
     *
     * @return \Cartalyst\Alerts\FlashNotifier
     */
    public function notifier($notifier)
    {
        return array_get($this->notifiers, $notifier, array_get($this->notifiers, $this->default));
    }

    /**
     * Sets the default notifier.
     *
     * @param  string  $notifier
     * @return void
     */
    public function setDefaultNotifier($notifier)
    {
        $this->default = $notifier;
    }

    /**
     * Returns the default notifier key.
     *
     * @return string
     */
    public function getDefaultNotifier()
    {
        return $this->default;
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
        if ($notifier = array_get($this->notifiers, $method)) {
            return $notifier;
        }

        return call_user_func_array([$this->notifiers[$this->default], '__call'], [$method, $parameters]);
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

        $messages = $this->filteredAlerts;

        if ( ! $this->filters && ! $this->filteredAlerts) {
            foreach ($this->notifiers as $notifier) {
                $messages = array_merge_recursive($messages, $notifier->all());
            }
        }

        $key = $exclude ? 'exclude' : 'include';

        if ($areas) {
            array_set($this->filters, "{$key}.areas", array_merge(array_get($this->filters, "{$key}.areas", []), $areas));
        }

        if ($types) {
            array_set($this->filters, "{$key}.types", array_merge(array_get($this->filters, "{$key}.types", []), $types));
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

        $this->filteredAlerts = $messages;
    }
}

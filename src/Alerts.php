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

namespace Cartalyst\Alerts;

use Cartalyst\Alerts\Notifiers\NotifierInterface;

class Alerts
{
    /**
     * The registered notifiers.
     *
     * @var array
     */
    protected $notifiers = [];

    /**
     * The default notifier.
     *
     * @var string
     */
    protected $defaultNotifier;

    protected $filters = [];

    protected $filteredAlerts = [];

    /**
     * Returns all the registered notifiers.
     *
     * @return array
     */
    public function getNotifiers()
    {
        return $this->notifiers;
    }

    /**
     * Adds the given notifier.
     *
     * @param  \Cartalyst\Alerts\Notifiers\NotifierInterface  $notifier
     * @return $this
     */
    public function addNotifier(NotifierInterface $notifier)
    {
        $this->notifiers[$notifier->getName()] = $notifier;

        return $this;
    }

    /**
     * Removes the given notifier.
     *
     * @param  string  $name
     * @return $this
     */
    public function removeNotifier($name)
    {
        unset($this->notifiers[$name]);

        return $this;
    }

    /**
     * Returns the default notifier name.
     *
     * @return string
     */
    public function getDefaultNotifier()
    {
        return $this->defaultNotifier;
    }

    /**
     * Sets the default notifier.
     *
     * @param  string  $notifier
     * @return $this
     */
    public function setDefaultNotifier($notifier)
    {
        $this->defaultNotifier = $notifier;

        return $this;
    }

    /**
     * Returns the given notifier.
     *
     * @param  string  $name
     * @param  string  $default
     * @return \Cartalyst\Alerts\Notifiers\NotifierInterface|null
     */
    public function notifier($name, $default = null)
    {
        return isset($this->notifiers[$name]) ? $this->notifiers[$name] : $default;
    }

    /**
     * Returns the alerts with the applied filters.
     *
     * @return array
     */
    public function get()
    {
        // Retrieve all alerts if no filters are assigned
        if ( ! $this->filters) {
            return $this->getAllAlerts();
        }

        $filteredAlerts = $this->filteredAlerts;

        // Clear filters and filtered alerts
        $this->filters = [];
        $this->filteredAlerts = [];

        return $filteredAlerts;
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
        if (isset($this->notifiers[$method])) {
            return $this->notifiers[$method];
        }

        if (strpos($method, 'on') !== false) {
            $area = strtolower(substr($method, 2));

            $messages = $this->whereArea($area)->get();

            foreach ($messages as $message) {
                if ($message->getKey() === $parameters[0]) {
                    return isset($parameters[1]) ? $parameters[1] : $message->message;
                }
            }

            return null;
        }

        return call_user_func_array(
            [ $this->notifiers[$this->defaultNotifier], '__call' ],
            [ $method, $parameters ]
        );
    }

    /**
     * Filter alerts based on the given areas.
     *
     * @param  string|array  $areas
     * @return self
     */
    public function whereArea($areas)
    {
        $this->registerFilter('area', $areas);

        return $this;
    }

    /**
     * Filter alerts excluding the given areas.
     *
     * @param  string|array  $areas
     * @return self
     */
    public function whereNotArea($areas)
    {
        $this->registerFilter('area', $areas, true);

        return $this;
    }

    /**
     * Filter alerts based on the given types.
     *
     * @param  string|array  $types
     * @return self
     */
    public function whereType($types)
    {
        $this->registerFilter('type', $types);

        return $this;
    }

    /**
     * Filter alerts excluding the given types.
     *
     * @param  string|array  $types
     * @return self
     */
    public function whereNotType($types)
    {
        $this->registerFilter('type', $types, true);

        return $this;
    }

    /**
     * Register the filter(s) on the given zone.
     *
     * @param  string  $zone
     * @param  string|array  $filters
     * @param  bool  $exclude
     * @return void
     */
    protected function registerFilter($zone, $filters, $exclude = false)
    {
        if ( ! is_array($filters)) {
            $filters = (array) $filters;
        }

        $alerts = $this->filteredAlerts;

        if ( ! $this->filters && ! $this->filteredAlerts) {
            $alerts = $this->getAllAlerts($alerts);
        }

        if ($filters) {
            $type = $exclude ? 'exclude' : 'include';

            $this->filters[$type][$zone] = array_merge(array_get($this->filters, "{$type}.{$zone}", []), $filters);

            $alerts = array_filter($alerts, function ($message) use ($zone, $filters, $exclude) {
                if ($exclude) {
                    return ! in_array($message->{$zone}, $filters);
                }

                return in_array($message->{$zone}, $filters);
            });
        }

        $this->filteredAlerts = $alerts;
    }

    /**
     * Returns all the alerts.
     *
     * @param  array  $alerts
     * @return array
     */
    protected function getAllAlerts(array $alerts = [])
    {
        foreach ($this->notifiers as $notifier) {
            $alerts = array_merge_recursive($alerts, $notifier->get());
        }

        return $alerts;
    }
}

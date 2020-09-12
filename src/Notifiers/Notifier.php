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
     * @param string $name
     * @param array  $config
     *
     * @return void
     */
    public function __construct(string $name, array $config = [])
    {
        $this->name = $name;

        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function alert($messages, string $type, string $area = 'default', bool $isFlash = false, ?string $class = null): void
    {
        $this->remove($type);

        if (! is_array($messages)) {
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
     * {@inheritdoc}
     */
    public function get(): array
    {
        return $this->alerts;
    }

    /**
     * Dynamically passes alerts to the view.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return void
     */
    public function __call(string $method, array $parameters): void
    {
        list($message, $area) = $this->parseParameters($parameters);

        $class = isset($this->config[$method]) ? $this->config[$method] : $method;

        $this->alert($message, $method, $area, false, $class);
    }

    /**
     * Removes the given type from messages.
     *
     * @param string $type
     *
     * @return void
     */
    protected function remove($type): void
    {
        unset($this->alerts[$type]);
    }

    /**
     * Parses parameters.
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function parseParameters($parameters): array
    {
        $message = $parameters[0] ?? null;

        $area = $parameters[1] ?? 'default';

        return [$message, $area];
    }
}

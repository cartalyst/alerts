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

namespace Cartalyst\Alerts;

class Message
{
    /**
     * Message.
     *
     * @var string
     */
    public $message;

    /**
     * Message type.
     *
     * @var string
     */
    public $type;

    /**
     * Message area.
     *
     * @var string
     */
    public $area;

    /**
     * Indicates whether the message is a flash message.
     *
     * @var bool
     */
    public $isFlash;

    /**
     * Message class.
     *
     * @var string
     */
    public $class;

    /**
     * The message identifier.
     *
     * @var string
     */
    protected $key;

    /**
     * Constructor.
     *
     * @param string      $message
     * @param string      $type
     * @param string      $area
     * @param bool        $isFlash
     * @param string|null $class
     * @param string|null $key
     *
     * @return void
     */
    public function __construct(string $message, string $type, string $area = 'default', bool $isFlash = false, ?string $class = null, ?string $key = null)
    {
        $this->message = $message;
        $this->type    = $type;
        $this->area    = $area;
        $this->isFlash = $isFlash;
        $this->class   = $class;
        $this->key     = $key;
    }

    /**
     * Returns the message identifier.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}

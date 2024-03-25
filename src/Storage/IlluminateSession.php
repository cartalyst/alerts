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
 * @version    8.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2024, Cartalyst LLC
 * @link       https://cartalyst.com
 */

namespace Cartalyst\Alerts\Storage;

use Illuminate\Session\Store as SessionStore;

class IlluminateSession implements StorageInterface
{
    /**
     * Session store object.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param \Illuminate\Session\Store $session
     *
     * @return void
     */
    public function __construct(SessionStore $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return $this->session->get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function flash(string $key, $value): void
    {
        $this->session->flash($key, $value);
    }
}

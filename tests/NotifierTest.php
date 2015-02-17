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

namespace Cartalyst\Alerts\Tests;

use Cartalyst\Alerts\Message;
use PHPUnit_Framework_TestCase;
use Illuminate\Support\MessageBag;
use Cartalyst\Alerts\Notifiers\Notifier;

class NotifierTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->notifier = new Notifier('flash');
    }

    /** @test */
    public function it_can_alert_and_retrieve_all()
    {
        $message = new Message('error message', 'error');

        $exptectedAlerts = [
            $message,
        ];

        $this->notifier->alert('error message', 'error');

        $this->assertEquals($exptectedAlerts, $this->notifier->get());
    }

    /** @test */
    public function it_can_retrieve_notifier_name()
    {
        $this->assertEquals('flash', $this->notifier->getName());
    }

    /** @test */
    public function it_can_set_unknown_types_of_alerts()
    {
        $this->notifier->foo('bar');

        $this->assertEquals('foo', head($this->notifier->get())->type);
    }

    /** @test */
    public function it_can_handle_message_bags()
    {
        $bag = new MessageBag(['foo' => 'bar']);

        $this->notifier->error($bag);

        $this->assertEquals('error', head($this->notifier->get())->type);
        $this->assertEquals('bar', head($this->notifier->get())->message);
    }
}

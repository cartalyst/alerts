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

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Cartalyst\Alerts\Message;

class MessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_construct_a_message()
    {
        $message = new Message('foo', 'form', 'default', false, 'class', 'foo_key');

        $this->assertEquals('foo', $message->message);
        $this->assertEquals('form', $message->type);
        $this->assertEquals('default', $message->area);
        $this->assertFalse($message->isFlash);
        $this->assertEquals('class', $message->class);
        $this->assertEquals('foo_key', $message->getKey());
    }
}

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
 * @version    3.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2019, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Alerts\Tests;

use Mockery as m;
use Cartalyst\Alerts\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_construct_a_message()
    {
        $message = new Message('foo', 'form', 'default', false, 'class', 'foo_key');

        $this->assertSame('foo', $message->message);
        $this->assertSame('form', $message->type);
        $this->assertSame('default', $message->area);
        $this->assertSame('class', $message->class);
        $this->assertSame('foo_key', $message->getKey());

        $this->assertFalse($message->isFlash);
    }
}

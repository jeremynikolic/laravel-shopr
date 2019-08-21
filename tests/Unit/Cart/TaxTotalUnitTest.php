<?php

namespace Happypixels\Shopr\Tests\Unit\Cart;

use Happypixels\Shopr\Facades\Cart;
use Happypixels\Shopr\Tests\TestCase;
use Happypixels\Shopr\Tests\Support\Models\TestShoppable;

class TaxTotalUnitTest extends TestCase
{
    /** @test */
    public function it_returns_the_total_tax()
    {
        config(['shopr.tax' => 25]);

        Cart::add(TestShoppable::first(), ['quantity' => 5]);

        // 25% tax of 2500 = 500. Subtotal should be 2000.
        $this->assertEquals(500, Cart::taxTotal());
    }
}

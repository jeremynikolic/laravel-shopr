<?php

namespace Happypixels\Shopr\Tests;

use Happypixels\Shopr\Contracts\Cart;
use Happypixels\Shopr\Repositories\SessionCartRepository;
use Happypixels\Shopr\Tests\Support\Models\TestShoppable;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return ['Happypixels\Shopr\ShoprServiceProvider'];
    }

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        $this->withFactories(__DIR__.'/../database/factories');

        // Set a dummy app key in order to encrypt cookies.
        config(['app.key' => 'base64:wbvPP9pBOwifnwu84BeKAVzmwM4TLvkVFowLaPAi6nA=']);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_shoppables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->decimal('price', 9, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        TestShoppable::create(['title' => 'Test product', 'price' => 500]);

        include_once __DIR__ . '/../database/migrations/create_order_tables.php.stub';
        include_once __DIR__ . '/../database/migrations/create_discount_coupons_table.php.stub';
        (new \CreateOrderTables())->up();
        (new \CreateDiscountCouponsTable())->up();
    }

    public function mockCart()
    {
        $mock = \Mockery::mock(SessionCartRepository::class);
        $this->app->instance(Cart::class, $mock);

        return $mock;
    }
}

<?php

namespace Tests\Unit;

use Orchestra\Testbench\TestCase;
use Siriondev\ConsellRepublica\Providers\ConsellRepublicaProvider;

class ConsellRepublicaTestCase extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ConsellRepublicaProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('cxr.validation.url', '*');

        $app['config']->set('cxr.validation.param', 'idCiutada');
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->idr = 'C-999-99999';

        $this->invalid_idr = 'invalid_idr';
    }
}

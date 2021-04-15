<?php

namespace Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Siriondev\ConsellRepublica\Exceptions\IDRFormatException;
use Siriondev\ConsellRepublica\Facades\IdentitatDigitalRepublicana;

class IDRepublicanaTest extends ConsellRepublicaTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->valid_idr = 'C-999-99999';

        $this->invalid_idr = 'invalid_idr';
    }

    /**
     * Test validate method accepts CxR valid API response
     *
     * @return void
     */
    public function test_facade_cxr_allows_valid_idr()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_ACTIVE'], 200)

        ]);

        $bool = IdentitatDigitalRepublicana::validate($this->valid_idr);

        $this->assertTrue($bool);
    }

    /**
     * Test validate method does not accept CxR invalid API response
     *
     * @return void
     */
    public function test_facade_cxr_does_not_allow_invalid_idr()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'NOT_VALID'], 200)

        ]);

        $bool = IdentitatDigitalRepublicana::validate($this->valid_idr);

        $this->assertFalse($bool);
    }

    /**
     * Test validate method does not accept invalid IDR format (C-999-99999)
     *
     * @return void
     */
    public function test_facade_does_not_allow_invalid_idr_format()
    {
        $this->expectException(IDRFormatException::class);

        $bool = IdentitatDigitalRepublicana::validate($this->invalid_idr);

        $this->assertFalse($bool);
    }

    /**
     * Test validate method accepts CxR valid API response
     *
     * @return void
     */
    public function test_validator_cxr_allows_valid_idr()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_ACTIVE'], 200)

        ]);

        $request = new Request([

            'id' => $this->valid_idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana',

        ]);

        $this->assertEquals($request->id, $this->valid_idr);
    }

    /**
     * Test validate method does not accept CxR invalid API response
     *
     * @return void
     */
    public function test_validator_cxr_does_not_allow_invalid_idr()
    {
        $this->expectException(ValidationException::class);

        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'NOT_VALID'], 200)

        ]);

        $request = new Request([

            'id' => $this->valid_idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana',

        ]);
    }

    /**
     * Test validate method does not accept invalid IDR format (C-999-99999)
     *
     * @return void
     */
    public function test_validator_does_not_allow_invalid_idr_format()
    {
        $this->expectException(IDRFormatException::class);

        $request = new Request([

            'id' => $this->invalid_idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana',

        ]);
    }
}

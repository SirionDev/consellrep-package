<?php

namespace Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Siriondev\ConsellRepublica\Exceptions\IDRFormatException;
use Siriondev\ConsellRepublica\Facades\IdentitatDigitalRepublicana;

class ValidatorTest extends ConsellRepublicaTestCase
{
    /**
     * Test valid API response
     *
     * @return void
     */
    public function test_validator_cxr_valid_idr()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_ACTIVE'], 200)

        ]);

        $request = new Request([

            'id' => $this->idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana',

        ]);

        $this->assertEquals($request->id, $this->idr);
    }

    /**
     * Test valid not active API response
     *
     * @return void
     */
    public function test_validator_cxr_valid_not_active_idr()
    {
        $this->expectException(ValidationException::class);

        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_NOT_ACTIVE'], 200)

        ]);

        $request = new Request([

            'id' => $this->idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana',

        ]);
    }

    /**
     * Test valid not active API response
     *
     * @return void
     */
    public function test_validator_cxr_valid_not_active_idr_arg_valid()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_NOT_ACTIVE'], 200)

        ]);

        $request = new Request([

            'id' => $this->idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana:valid',

        ]);

        $this->assertEquals($this->idr, $request->id);
    }

    /**
     * Test valid not active API response
     *
     * @return void
     */
    public function test_validator_cxr_valid_not_active_idr_arg_valid_and_active()
    {
        $this->expectException(ValidationException::class);

        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_NOT_ACTIVE'], 200)

        ]);

        $request = new Request([

            'id' => $this->idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana:valid,active',

        ]);
    }

    /**
     * Test underaged valid API response
     *
     * @return void
     */
    public function test_validator_cxr_underaged_active_idr_arg_valid_active_and_underaged()
    {
        $this->expectException(ValidationException::class);

        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'UNDERAGED_ACTIVE'], 200)

        ]);

        $request = new Request([

            'id' => $this->idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana:valid,active,underaged',

        ]);
    }

    /**
     * Test underaged valid API response
     *
     * @return void
     */
    public function test_validator_cxr_not_valid_idr()
    {
        $this->expectException(ValidationException::class);

        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'NOT_VALID'], 200)

        ]);

        $request = new Request([

            'id' => $this->idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana',

        ]);
    }

    /**
     * Test valid unknown API response
     *
     * @return void
     */
    public function test_validator_cxr_valid_unknown()
    {
        $this->expectException(ValidationException::class);

        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_UNKNOWN'], 200)

        ]);

        $request = new Request([

            'id' => $this->idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana',

        ]);
    }

    /**
     * Test valid unknown API response
     *
     * @return void
     */
    public function test_validator_cxr_valid_unknown_arg_active()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_UNKNOWN'], 200)

        ]);

        $request = new Request([

            'id' => $this->idr

        ]);

        $request->validate([

            'id' => 'required|idrepublicana:valid',

        ]);

        $this->assertEquals($this->idr, $request->id);
    }
}

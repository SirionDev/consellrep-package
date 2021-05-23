<?php

namespace Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Siriondev\ConsellRepublica\Exceptions\IDRFormatException;
use Siriondev\ConsellRepublica\Facades\IdentitatDigitalRepublicana;

class FacadeTest extends ConsellRepublicaTestCase
{
    /**
     * Test valid API response
     *
     * @return void
     */
    public function test_facade_cxr_valid_idr()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_ACTIVE'], 200)

        ]);

        $idr = IdentitatDigitalRepublicana::validate($this->idr);

        $this->assertTrue($idr->isValid());

        $this->assertTrue($idr->isActive());

        $this->assertFalse($idr->isUnderaged());
    }

    /**
     * Test valid not active API response
     *
     * @return void
     */
    public function test_facade_cxr_valid_not_active_idr()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_NOT_ACTIVE'], 200)

        ]);

        $idr = IdentitatDigitalRepublicana::validate($this->idr);

        $this->assertTrue($idr->isValid());

        $this->assertTrue($idr->isFormat());

        $this->assertFalse($idr->isActive());

        $this->assertFalse($idr->isUnderaged());
    }

    /**
     * Test valid underaged API response
     *
     * @return void
     */
    public function test_facade_cxr_valid_underaged_idr()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_UNDERAGED'], 200)

        ]);

        $idr = IdentitatDigitalRepublicana::validate($this->idr);

        $this->assertTrue($idr->isValid());

        $this->assertTrue($idr->isFormat());

        $this->assertTrue($idr->isActive());

        $this->assertTrue($idr->isUnderaged());
    }

    /**
     * Test not valid API response
     *
     * @return void
     */
    public function test_facade_cxr_not_valid_idr()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'NOT_VALID'], 200)

        ]);

        $idr = IdentitatDigitalRepublicana::validate($this->idr);

        $this->assertFalse($idr->isValid());

        $this->assertTrue($idr->isFormat());

        $this->assertFalse($idr->isActive());

        $this->assertFalse($idr->isUnderaged());
    }

    /**
     * Test valid unknown API response
     *
     * @return void
     */
    public function test_facade_cxr_valid_unknown_idr()
    {
        Http::fake([

            config('cxr.validation.url') => Http::response(['state' => 'VALID_UNKNOWN'], 200)

        ]);

        $idr = IdentitatDigitalRepublicana::validate($this->idr);

        $this->assertTrue($idr->isValid());

        $this->assertTrue($idr->isFormat());

        $this->assertFalse($idr->isActive());

        $this->assertFalse($idr->isUnderaged());
    }

    /**
     * Test invalid IDR
     *
     * @return void
     */
    public function test_facade_cxr_invalid_idr()
    {
        $idr = IdentitatDigitalRepublicana::validate($this->invalid_idr);

        $this->assertFalse($idr->getStatus());

        $this->assertFalse($idr->isFormat());

        $this->assertEquals($idr->getMessage(), trans('consellrep::validation.idrepublicana.format'));
    }
}

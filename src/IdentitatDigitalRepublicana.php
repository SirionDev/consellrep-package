<?php

namespace Siriondev\ConsellRepublica;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Siriondev\ConsellRepublica\Exceptions\ConfigException;
use Siriondev\ConsellRepublica\Exceptions\IDRFormatException;

class IdentitatDigitalRepublicana
{
    /**
     * Valida la Identitat Digital Republicana introduïda
     *
     * @param string $idr
     * @return bool
     */
    public function validate(string $idr): bool
    {
        $validation_path = config('cxr.validation.url');

        $validation_param = config('cxr.validation.param');

        if (empty($validation_path) || empty($validation_param))

            throw new ConfigException(trans('consellrep::validation.missing_config'), 1);

        if (!preg_match("/[A-Za-z]{1}\-[0-9]{3}\-[0-9]{5}/", $idr))

            throw new IDRFormatException(trans('consellrep::validation.bad_format'), 1);

        try {

            $response = Http::get($validation_path, [

                $validation_param => $idr

            ]);

            return isset($response['state']) && $response['state'] == 'VALID_ACTIVE';

        } catch (\Throwable $e) {

            throw new \Exception(trans('consellrep::validation.bad_request'), 1);

        }
    }
}

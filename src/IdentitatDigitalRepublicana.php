<?php

namespace Siriondev\ConsellRepublica;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Siriondev\ConsellRepublica\Exceptions\ConfigException;

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

            throw new ConfigException(trans('consellrep::idrep.missing_config'), 1);

        try {

            $response = Http::get($validation_path, [

                $validation_param => $idr

            ]);

            return isset($response['state']) && $response['state'] == 'VALID_ACTIVE';

        } catch (\Throwable $e) {

            throw new \Exception(trans('consellrep::idrep.bad_request'), 1);

        }
    }
}

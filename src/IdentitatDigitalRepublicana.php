<?php

namespace Siriondev\ConsellRepublica;

use Illuminate\Support\Facades\Http;

class IdentitatDigitalRepublicana
{
    /**
     * Valida la Identitat Digital Republicana introduïda
     *
     * @param string $idr
     * @return bool
     */
    public function validate(string $idr): IDRValidator
    {
        $idr_validator = new IDRValidator($idr);

        $validation_path = config('cxr.validation.url');

        $validation_param = config('cxr.validation.param');

        if (empty($validation_path) || empty($validation_param)) {

            $idr_validator->setMessage(trans('consellrep::validation.missing_config'));

        } elseif (!$idr_validator->isFormat()) {

            $idr_validator->setMessage(trans('consellrep::validation.idrepublicana.format'));

        } else {

            try {

                $response = Http::get($validation_path, [

                    $validation_param => $idr

                ]);

                $idr_validator->parse($response);

            } catch (\Throwable $e) {

                $idr_validator->setMessage(trans('consellrep::validation.bad_request'));

            }
        }

        return $idr_validator;
    }
}

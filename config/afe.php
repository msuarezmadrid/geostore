<?php

return [
    'url'                   => env('AFE_API_URL', '127.0.0.1'),
    'url_afe'               => env('AFE_URL', '127.0.0.1'),
    'company_rut'           => env('AFE_RUT_COMPANY', ''),
    'user_rut'              => env('AFE_RUT_USER', ''),
    'default_rut'           => env('AFE_DEFAULT_RUT', ''),
    'company_email'         => env('AFE_EMAIL_COMPANY',''),
    'company_industries'    => env('AFE_INDUSTRIES_COMPANY',''),
    'company_address'       => env('AFE_ADDRESS_COMPANY', ''),
    'company_name'          => env('AFE_NAME_COMPANY',''),
    'afe_token'             => env('AFE_TOKEN',''),
    'company_acteco'        => env('AFE_ACTECO_COMPANY', ''),
    'use_params'            => env('AFE_USE_ENV_PARAMS', false)
];
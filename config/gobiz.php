<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Go-jek Client ID
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Go-biz API client ID.
    |
    | Description from official documentation:
    |
    | "Client identifier issued by GoJek and must be same- 
    |  as the one sent during /oauth2/auth"
    |
    */
    'client_id' => env('GOBIZ_CLIENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Go-jek Client Secret
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Go-biz API client ID.
    |
    */
    'client_secret' => env('GOBIZ_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Go-biz API Base URL
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Go-biz API integration base URL.
    | This base URL will be used for the implementation for API request.
    |
    | This configuration can guess which ENV value will be used.
    |
    */
    'base_url' => which_env(
    	env('GOBIZ_APP_ENV', 'local') == 'local', // Condition
    	'GOBIZ_API_SANDBOX_BASE_URL', // if true use this
    	'GOBIZ_API_BASE_URL', // if false use this
        'https://api.sandbox.gobiz.co.id/' // if selected env default value
    ),

    /*
    |--------------------------------------------------------------------------
    | Go-biz API OAUTH URL
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Go-biz API integration base URL.
    | This API URL will be used to authenticate the Go-biz request.
    |
    | This configuration can guess which ENV value will be used.
    |
    */
    'oauth_url' => which_env(
    	env('GOBIZ_APP_ENV', 'local') == 'local', // Condition
    	'GOBIZ_API_SANDBOX_OAUTH_URL', // if true use this
    	'GOBIZ_API_OAUTH_URL', // if false use this
        'https://integration-goauth.gojekapi.com/' // if selected env default value
    ),

    /*
    |--------------------------------------------------------------------------
    | Go-biz Enterprise ID
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Go-biz API enterprise ID.
    |
    | To see the description please see:
    | https://docs.gobiz.co.id/api-reference/index.html#enterprise-outlet-information
    |
    */
    'enterprise_id' => env('GOBIZ_ENTERPRISE_ID'),

    /*
    |--------------------------------------------------------------------------
    | Go-biz Code
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Go-biz API Auth Code.
    |
    | To see the description please see:
    | https://docs.gobiz.co.id/docs/index.html#:~:text=must%20be%20authorization_code.-,code,-string
    |
    */
    'code' => env('GOBIZ_CODE'),

    /*
    |--------------------------------------------------------------------------
    | Go-biz Redirect Url
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for Go-biz API Auth Code.
    |
    | To see the description please see:
    | https://docs.gobiz.co.id/docs/index.html#:~:text=redirection%20to%20redirect_uri.-,redirect_uri,-string
    |
    */
    'redirect_uri' => env('GOBIZ_REDIRECT_URI'),
];
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/*',
        '/payment/ipay88/response',
        '/payment/ipay88/backend',
        '/checkout',
        '/payment/dragonpay/postback',
        '/payment/dragonpay/post',
        '/tablet/get_data',
        '/member/payroll/get_cutoff_data',
        '/member/payroll/api_login',
        '/member/mlm/developer/import',
        '/member/payroll/biometrics/save_data',
        '/payment/paymaya/webhook/*',
        '/member/mlm/developer/recompute'

    ];
}

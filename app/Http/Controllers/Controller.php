<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function client()
    {
        /**
         * SignTokenGuard 验证
         * logger(Signature::signer('hmac')->setKey('986icjfspfow895zi4k6bv4r37ymyv3k')->sign($this->request->except([$this->inputKey])), ['client_api_guard_signature']);
         */
        return auth('api')->user();
    }
}

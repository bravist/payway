<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use League\OAuth2\Server\ResourceServer;
use Illuminate\Http\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Laravel\Passport\TokenRepository;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

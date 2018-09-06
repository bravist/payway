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
   
    /**
     * The Resource Server instance.
     *
     * @var \League\OAuth2\Server\ResourceServer
     */
    protected $server;

    protected $token;

    /**
     * Create a new middleware instance.
     *
     * @param  \League\OAuth2\Server\ResourceServer  $server
     * @return void
     */
    public function __construct(ResourceServer $server, TokenRepository $token)
    {
        $this->server = $server;
        $this->token = $token;
    }

    /**
     * Global token
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function retrieveTokenByRequest(Request $request)
    {
        $psr = (new DiactorosFactory)->createRequest($request);
        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
            return $this->token->find($psr->getAttribute('oauth_access_token_id'));
        } catch (OAuthServerException $e) {
            throw new AuthenticationException;
        }
    }
}

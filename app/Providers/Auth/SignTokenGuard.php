<?php

namespace App\Providers\Auth;

use Illuminate\Auth\TokenGuard;
use Liyu\Signature\Facade\Signature;

class SignTokenGuard extends TokenGuard
{
    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user)) {
            return $this->user;
        }
        
        $token = $this->getTokenForRequest();
        $user = $this->retrieveByToken($token);
        
        return $this->user = $user;
    }
    
    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->inputKey])) {
            return false;
        }
        
        $token = $credentials[$this->inputKey];
        if ($this->retrieveByToken($token)) {
            return true;
        }
        
        return false;
    }
    
    public function retrieveByToken($token)
    {
        if (empty($token)) {
            return null;
        }
        // 签名算法
        if (is_null($algo = config("signature.hmac.options.algo"))) {
            return null;
        }
        // 从token中取出appId和签名sign
        list($appId, $sign) = explode(':', $token, 2);
        if (empty($appId) || empty($sign)) {
            return null;
        }
        // 用appId从DB中查找记录
        $client = $this->provider->retrieveById($appId);
        // 获取加密密钥
        if (empty($secret = $client[$this->storageKey])) {
            return null;
        }
        // 验证签名
        $verify = Signature::signer('hmac')
        ->setAlgo($algo)
        ->setKey($secret)
        ->verify($sign, $this->request->except([$this->inputKey]));
        if (!$verify) {
            return null;
        }
        
        return $client;
    }
}

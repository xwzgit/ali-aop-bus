<?php
namespace AopBus;

use AopBus\Support\Aop\AopClient;
use AopBus\Support\Log\Log;
use AopBus\Traits\UserCertify\UserCertify;
use AopBus\Config\Config;

/**
 * Created by PhpStorm.
 * User: owner
 * Date: 2019-12-10
 * Time: 17:00
 * Project Name: aliAopBus
 */


class AliAop
{
    use UserCertify;
    static $app ;

    protected $config = [];

    protected $client ;

    public function __construct($appId,$preKey,$pubKey)
    {
        if(!isset(self::$app['aliAop'])  ||  !(($this->client = self::$app['aliAop']) instanceof AopClient)) {
            $this->client = new AopClient();

            $this->client->appId = $appId;
            $this->client->rsaPrivateKey = $preKey;
            $this->client->alipayrsaPublicKey = $pubKey;
            $this->client->signType = 'RSA2';
            self::$app['aliAop'] = $this->client;
        }

        $this->client = self::$app['aliAop'];
    }


}
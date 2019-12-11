<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 2019-12-11
 * Time: 11:01
 * Project Name: aliAopBus
 */
namespace AopBus\Traits\UserCertify;

use AopBus\Ali\User\Certify\AlipayUserCertifyOpenCertifyRequest;
use AopBus\Ali\User\Certify\AlipayUserCertifyOpenInitializeRequest;
use AopBus\Ali\User\Certify\AlipayUserCertifyOpenQueryRequest;

trait UserCertify
{
    /**
     * 支付宝实名认证初始化获取CertifyId
     *
     * @param $orderNo
     * @param $certName
     * @param $certNo
     * @param $returnUrl
     * @return mixed
     */
    public function userCertifyInstall($orderNo,$certName,$certNo,$returnUrl)
    {
        $request = new AlipayUserCertifyOpenInitializeRequest();
        $bizContent = json_encode([
            'outer_order_no' => $orderNo,
            'biz_code' => 'FACE',
            'identity_param' => [
                'identity_type' => 'CERT_INFO',
                'cert_type' => 'IDENTITY_CARD',
                'cert_name' => $certName,
                'cert_no' => $certNo,
            ],
            'merchant_config' => [
                'return_url' => $returnUrl
            ],
        ],JSON_UNESCAPED_UNICODE);

        $request->setBizContent($bizContent);

        $result = $this->client->execute($request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName())."_response";

        return $result->$responseNode;
    }

    /**
     * 通过认证Id进行用户认证
     *
     * @param $certifyId
     * @param $method
     * @return mixed
     */
    public function userCertifyRequest($certifyId,$method = 'GET')
    {
        $request = new AlipayUserCertifyOpenCertifyRequest();
        $bizContent = json_encode([
            'certify_id' => $certifyId,
        ],JSON_UNESCAPED_UNICODE);

        $request->setBizContent($bizContent);

        return $result = $this->client->pageExecute($request,$method);

    }

    /**
     * 获取认证地址,包含认证初始化，返回认证地址
     *
     * @param $orderNo
     * @param $certName
     * @param $certNo
     * @param $returnUrl
     */
    public function userCertifyGetRequest($orderNo,$certName,$certNo,$returnUrl)
    {

        $result = $this->userCertifyInstall($orderNo,$certName,$certNo,$returnUrl);

        if(isset($result->code) && $result->code == 10000 ) {
            $certifyUrl = $this->userCertifyRequest($result->certify_id);
            echo $certifyUrl;
        }
    }

    /**
     * 认证查询
     *
     * @param $certifyId
     * @return mixed
     */
    public function userCertifyQuery($certifyId)
    {
        $request = new AlipayUserCertifyOpenQueryRequest();
        $bizContent = json_encode([
            'certify_id' => $certifyId,
        ],JSON_UNESCAPED_UNICODE);

        $request->setBizContent($bizContent);
        $result =  $this->client->execute($request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName())."_response";
        return $result->$responseNode;
    }
}
<?php


namespace App\Services;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class AliSms
{
    public static function sendSmsCode($mobile, $code,$templateCode)
    {
        $config = config('aliyunsms');
        $templateParam = json_encode(['code'=>$code]);

        try {
            AlibabaCloud::accessKeyClient($config['AccessKeyId'], $config['AccessKeySecret'])
                ->regionId($config['regionId']) // replace regionId as you need
                ->asDefaultClient();

            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'PhoneNumbers' => $mobile,
                        'SignName' => $config['SignName'],
                        'TemplateCode' => $templateCode,
                        'TemplateParam' => $templateParam,
                        'RegionId' => $config['regionId'],
                    ],
                ])
                ->request();
            return $result->toArray();
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }

}

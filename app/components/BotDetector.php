<?php
namespace app\components;

use yii\base\Component;
use yii\helpers\Json;
use yii\httpclient\Client;

class BotDetector extends Component
{
    public $apiUrl = 'http://qnits.net/api/checkUserAgent';

    public function isBot($userAgent): bool
    {
        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->apiUrl)
                ->setData(['userAgent' => $userAgent])
                ->send();
            if ($response->isOk) {
                $data = Json::decode($response->content);
                return !empty($data['isBot']) && $data['isBot'] === true;
            }
        } catch (\Exception $e) {
            \Yii::error('BotDetector error: ' . $e->getMessage());
        }
        return false;
    }
}
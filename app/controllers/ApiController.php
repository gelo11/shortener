<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionShorten()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $url = Yii::$app->request->post('url');
        if (empty($url)) {
            return ['error' => 'URL is required'];
        }
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return ['error' => 'Invalid URL'];
        }
        $link = \app\models\Link::getOrCreate($url);
        $shortUrl = Yii::$app->request->hostInfo . '/' . $link->short_code;
        return ['short_url' => $shortUrl];
    }
}
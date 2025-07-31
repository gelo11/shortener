<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Link;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRedirect($code)
    {
        $link = Link::find()->where(['short_code' => $code])->one();
        if (!$link) {
            throw new \yii\web\NotFoundHttpException('Ссылка не найдена');
        }
        Yii::$app->response->redirect($link->original_url, 301)->send();
        $this->recordVisit($link->id, Yii::$app->request->userAgent);
        Yii::$app->end();
    }

    private function recordVisit($linkId, $userAgent)
    {
        if (Yii::$app->botDetector->isBot($userAgent)) {
            return;
        }
        $visit = new \app\models\Visit();
        $visit->link_id = $linkId;
        $visit->user_agent = $userAgent;
        $visit->save(false);
    }
}
<?php
namespace tests\unit;

use Yii;
use app\models\Link;
use app\models\Visit;
use yii\db\Connection;
use yii\queue\file\Queue;
use Codeception\Test\Unit;

class LinkTest extends Unit
{
    protected function _before()
    {
        $this->cleanTables();
    }

    private function cleanTables()
    {
        Yii::$app->db->createCommand()->delete('visits')->execute();
        Yii::$app->db->createCommand()->delete('links')->execute();
    }

    public function testGenerateUniqueShortCode()
    {
        $code1 = Link::generateShortCode();
        $code2 = Link::generateShortCode();
        $this->assertNotEquals($code1, $code2);
    }

    public function testShortCodeCaseSensitive()
    {
        $link1 = new Link();
        $link1->original_url = 'http://test.com';
        $link1->short_code = 'AbC123';
        $link1->save(false);

        $link2 = new Link();
        $link2->original_url = 'http://other.com';
        $link2->short_code = 'abc123';
        $link2->save(false);

        $found1 = Link::find()->where(['short_code' => 'AbC123'])->one();
        $found2 = Link::find()->where(['short_code' => 'abc123'])->one();

        $this->assertEquals('AbC123', $found1->short_code);
        $this->assertEquals('abc123', $found2->short_code);
    }

    public function testGetOrCreateReturnsSameLinkForSameUrl()
    {
        $url = 'https://example.com';
        $link1 = Link::getOrCreate($url);
        $link2 = Link::getOrCreate($url);
        $this->assertEquals($link1->id, $link2->id);
    }

    public function testVisitNotSavedForBot()
    {
        $link = new Link();
        $link->original_url = 'http://test.com';
        $link->short_code = 'XyZ789';
        $link->save(false);

        $controller = new \app\controllers\SiteController('site', Yii::$app);

        $botDetector = $this->getMockBuilder(\app\components\BotDetector::class)
            ->onlyMethods(['isBot'])
            ->getMock();
        $botDetector->method('isBot')->willReturn(true);

        Yii::$app->set('botDetector', $botDetector);

        $method = new \ReflectionMethod($controller, 'recordVisit');

        $result = $method->invoke($controller, $link->id, 'Googlebot');

        $this->assertNull($result);
        $this->assertEquals(0, Visit::find()->count());
    }
}
<?php
namespace app\models;

use yii\db\ActiveRecord;

class Link extends ActiveRecord
{
    public static function tableName()
    {
        return 'links';
    }

    public function getVisits()
    {
        return $this->hasMany(Visit::class, ['link_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        }
        return false;
    }

    public static function generateShortCode()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (self::find()->where(['short_code' => $code])->exists());
        return $code;
    }

    public static function getOrCreate($url)
    {
        $link = self::find()->where(['original_url' => $url])->one();
        if ($link) {
            return $link;
        }
        $link = new self();
        $link->original_url = $url;
        $link->short_code = self::generateShortCode();
        $link->save(false);
        return $link;
    }
}
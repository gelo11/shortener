<?php
namespace app\models;

use yii\db\ActiveRecord;

class Visit extends ActiveRecord
{
    public static function tableName()
    {
        return 'visits';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->visited_at = time();
            }
            return true;
        }
        return false;
    }
}
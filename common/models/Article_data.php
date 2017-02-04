<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_data".
 *
 * @property string $aid
 * @property string $content
 */
class Article_data extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid', 'content'], 'required'],
            [['aid'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'content' => 'Content',
        ];
    }
}

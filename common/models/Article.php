<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $aid
 * @property integer $cateid
 * @property string $title
 * @property string $flag
 * @property string $cover
 * @property string $author
 * @property string $source
 * @property string $keywords
 * @property string $description
 * @property integer $vieworder
 * @property string $hits
 * @property string $linkurl
 * @property string $create_time
 * @property string $modify_time
 * @property integer $isrecycle
 * @property integer $status
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cateid', 'vieworder', 'hits', 'create_time', 'modify_time', 'isrecycle', 'status'], 'integer'],
            [['flag', 'cover', 'author', 'source', 'keywords', 'linkurl'], 'required'],
            [['title', 'cover'], 'string', 'max' => 100],
            [['flag', 'author', 'source'], 'string', 'max' => 20],
            [['keywords'], 'string', 'max' => 60],
            [['description'], 'string', 'max' => 255],
            [['linkurl'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'cateid' => 'Cateid',
            'title' => '标题',
            'flag' => 'Flag',
            'cover' => 'Cover',
            'author' => 'Author',
            'source' => 'Source',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'vieworder' => 'Vieworder',
            'hits' => 'Hits',
            'linkurl' => 'Linkurl',
            'create_time' => 'Create Time',
            'modify_time' => 'Modify Time',
            'isrecycle' => 'Isrecycle',
            'status' => 'Status',
        ];
    }
}

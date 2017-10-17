<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tweet".
 *
 * @property int $id
 * @property int $keyword_id
 * @property int $user_id
 * @property int $country_id
 * @property string $country_name
 * @property string $city_name
 * @property string $location
 * @property string $coordinates
 * @property string $altitude
 * @property string $longtitude
 * @property string $description
 * @property string $tweet_owner
 * @property string $profile_image
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Keyword $keyword
 * @property User $user
 */
class Tweet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tweet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword_id', 'user_id'], 'required'],
            [['keyword_id', 'user_id', 'country_id', 'created_at', 'updated_at'], 'integer'],
            [['country_name', 'city_name', 'location', 'coordinates', 'altitude', 'longtitude', 'description', 'tweet_owner', 'profile_image'], 'string', 'max' => 255],
            [['keyword_id'], 'exist', 'skipOnError' => true, 'targetClass' => Keyword::className(), 'targetAttribute' => ['keyword_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'keyword_id' => Yii::t('app', 'Keyword ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'country_id' => Yii::t('app', 'Country ID'),
            'country_name' => Yii::t('app', 'Country Name'),
            'city_name' => Yii::t('app', 'City Name'),
            'location' => Yii::t('app', 'Location'),
            'coordinates' => Yii::t('app', 'Coordinates'),
            'altitude' => Yii::t('app', 'Altitude'),
            'longtitude' => Yii::t('app', 'Longtitude'),
            'description' => Yii::t('app', 'Description'),
            'tweet_owner' => Yii::t('app', 'Tweet Owner'),
            'profile_image' => Yii::t('app', 'Profile Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function() { return date('U');},
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeyword()
    {
        return $this->hasOne(Keyword::className(), ['id' => 'keyword_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

<?php

namespace advance\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 *
 * @property EventTimes[] $eventTimes
 * @property EventBids[] $eventBids
 * @property EventCustoms[] $eventCustoms
 */
class Events extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{events}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Событие',
            'description' => 'Описание',
        ];
    }
    
    /**
     * Gets query for [[EventTimes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventTimes()
    {
        return $this->hasMany(EventTimes::className(), ['eventId' => 'id']);
    }
    
    /**
     * Gets query for [[EventBids]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventBids()
    {
        return $this->hasMany(EventBids::className(), ['eventId' => 'id']);
    }
    
    /**
     * Gets query for [[EventBids]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventCustoms()
    {
        return $this->hasMany(EventCustom::className(), ['eventId' => 'id']);
    }
    
}


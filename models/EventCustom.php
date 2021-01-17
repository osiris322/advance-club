<?php

namespace advance\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "eventCustom".
 *
 * @property int $id
 * @property int $eventId
 * @property string $date
 *
 * @property Event $event
 * @property EventCustomTimes[] $eventCustomTimes
 */
class EventCustom extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{eventCustom}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eventId', 'date'], 'required'],
            [[ 'eventId'], 'integer'],
            ['date', 'format' => 'php:Y-m-d'],
            [['eventId'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['eventId' => 'id']],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eventId' => 'Event Id',
            'date' => 'Date',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'eventId']);
    }
    
    /**
     * Gets query for [[EventTimes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventCustomTimes()
    {
        return $this->hasMany(EventCustomTimes::className(), ['customId' => 'id']);
    }
}


<?php

namespace advance\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "eventBids".
 *
 * @property int $id
 * @property int $eventId
 * @property string $date
 * @property int $time
 * @property string $name
 * @property string $contact
 *
 * @property Event $event
 */
class EventBids extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{eventBids}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eventId', 'date', 'time', 'name', 'contact'], 'required'],
            [[ 'eventId'], 'integer'],
            ['date', 'format' => 'php:Y-m-d'],
            [['time'], 'integer', 'min' => 8, 'max' => 20],
            [['name'], 'string', 'max' => 255],
            [['contact'], 'string'],
            [['eventId', 'date', 'time'], 'unique', 'targetAttribute' => ['eventId', 'date', 'time']],
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
            'time' => 'Time',
            'name' => 'Name',
            'contact' => 'Contact',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'eventId']);
    }
}


<?php

namespace advance\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "eventTimes".
 *
 * @property int $id
 * @property int $eventId
 * @property int $weekDay
 * @property int $time
 *
 * @property Event $event
 */
class EventTimes extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{eventTimes}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eventId', 'weekDay', 'time'], 'required'],
            [[ 'eventId', 'weekDay'], 'integer'],
            //DateTime::format N исключаем воскресенье
            ['weekDay', 'in', 'range' => [1, 2, 3, 4, 5, 6]],
            [['time'], 'integer', 'min' => 8, 'max' => 20],
            [['eventId', 'weekDay', 'time'], 'unique', 'targetAttribute' => ['eventId', 'weekDay', 'time']],
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
            'weekDay' => 'Week day',
            'time' => 'Time',
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
     * венруть список вариантов Weekday
     * @return array
     */
    public static function getListWeekDay() {
        return [
            1 => 'ПН',
            2 => 'ВТ',
            3 => 'СР',
            4 => 'ЧТ',
            5 => 'ПТ',
            6 => 'СБ',
        ];
    }
    
    /**
     * Узнаем номер недели из даты
     * @param string $date
     * @return int
     */
    public static function findWeekDayDate($date) {
        $time = strtotime($date);
        $dt = new \DateTime(date('Y-m-d', $time));
        return $dt->format("N");
    }
}


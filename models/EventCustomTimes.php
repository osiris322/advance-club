<?php

namespace advance\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "eventCustomTimes".
 *
 * @property int $id
 * @property int $customId
 * @property int $time
 *
 * @property EventCustom $eventCustom
 */
class EventCustomTimes extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{eventCustomTimes}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customId', 'time'], 'required'],
            [[ 'customId'], 'integer'],
            [['time'], 'integer', 'min' => 8, 'max' => 20],
            [['customId', 'time'], 'unique', 'targetAttribute' => ['customId', 'time']],
            [['customId'], 'exist', 'skipOnError' => true, 'targetClass' => EventCustom::className(), 'targetAttribute' => ['customId' => 'id']],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customId' => 'Custom Id',
            'time' => 'Time',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCustom()
    {
        return $this->hasOne(EventCustom::className(), ['id' => 'customId']);
    }
}


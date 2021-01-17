<?php
namespace advance\controllers;

use yii\rest\ActiveController;

class EventTimesController extends ActiveController {    

    public $modelClass = 'advance\models\EventTimes';

    public function behaviors() {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
    /**
     * curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://localhost/event-times
     * curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://localhost/event-times/1
     * curl -X PUT -H "Content-Type: application/json" -d '{"name":"subject2"}' "http://localhost/events/update?id=1"
     * curl --data "eventId=1&weekDay=1&time=12:00" http://localhost/event-times/create
     */
    

}

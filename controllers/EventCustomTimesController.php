<?php
namespace advance\controllers;

use yii\rest\ActiveController;

class EventCustomTimesController extends ActiveController {    

    public $modelClass = 'advance\models\EventCustomTimes';

    public function behaviors() {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
    

}

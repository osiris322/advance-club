<?php
namespace advance\controllers;

use yii\rest\ActiveController;

class EventCustomController extends ActiveController {    

    public $modelClass = 'advance\models\EventCustom';

    public function behaviors() {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
    

}

<?php
namespace advance\controllers;

use yii\rest\ActiveController;

class EventBidsController extends ActiveController {    

    public $modelClass = 'advance\models\EventBids';

    public function behaviors() {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }
    

}

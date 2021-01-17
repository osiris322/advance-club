<?php

namespace advance\controllers;

use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

use advance\models\MapEvent;
use advance\models\Events;

/**
 * Запросы для работы с ORM моделью 
 * get весь список
 * curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://localhost/events
 * get событие
 * curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://localhost/events/1
 * curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X OPTIONS http://localhost/events/options
 * обновить
 * curl -X PUT -H "Content-Type: application/json" -d '{"name":"subject2"}' "http://localhost/events/update?id=1"
 * записать
 * curl -d "name=112&description=sasas" -X POST http://localhost/events/create

 * для вызова find-map-by-date используеться следующий формат
 * curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://localhost/events/find-map-by-date?date=2021-01-12
 * curl  -X GET  'http://localhost/events/find-time-map?date=2021-01-15&eventId=1'
 */
class EventsController extends ActiveController {

    public $modelClass = 'advance\models\Events';

    public function behaviors() {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }

    /**
     * возвращает структуру для дальнейшей
     * визуализации расписания списка всех событий на конкретную дату
     * @param string $date format: Y-m-d
     * @return array
     */
    public function actionFindMapByDate(string $date) {
        $mapEvent = new MapEvent();
        
        return $mapEvent->findMapByDate($date);
    }
    
    public function actionFindTimeMap(string $date, int $eventId, string $maxDate = null) {
        $this->findEvent($eventId);
        $mapEvent = new MapEvent();
        
        return $mapEvent->findTimeMap($date, $eventId, $maxDate);
    }

    /**
     * проверка на существование события
     * @param int $id
     * @throws NotFoundHttpException
     */
    protected function findEvent(int $id)
    {
        if (($model = Events::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        
    }
    
}

<?php

namespace advance\models;

use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

/**
 * IncidentSearch represents the model behind the search form of `app\models\incident\Incident`.
 */
class MapEvent extends EventsSearch {

    /**
     *
     * @var int номер недели
     */
    private $weekDayN;

    /**
     *
     * @var int
     */
    private $eventId;

    /**
     *
     * @var string дата для расчета собтия
     */
    private $useDate;

    /**
     *  Массив событий на дату|ы
     * @var array
     * 
     * Пимер
      "yyyy-mm-dd" {
            "1": {
            //событие
                "event": {
                    "id": 1,
                    "name": "subject2",
                    "description": "Примечание 1"
                },
                // время событий где ключ это час события, если false значит событие свободное
                "times": {
                    "8": {  // есть запись
                    "id": 1,
                    "eventId": 1,
                    "date": "2021-01-12",
                    "time": 8,
                    "name": "jhg",
                    "contact": "123"
                    },
                    "9": null // неть записи
                }
            },
            "2": .....
      }
      .....
     */
    private $mapByDate = [];

    /**
     * дата начала событий
     * @var \DataTime
     */
    private $dtStart;

    /**
     * конечная дата событий
     * @var \DataTime
     */
    private $dtEnd;

    /**
     * 
     * @param string $date
     * @return array
     */
    public function findMapByDate(string $date) {
        $this->validateDate($date);
        $this->useDate = $this->getFormatDate($date);
        $this->weekDayN = EventTimes::findWeekDayDate($date);
        $this->sortArrayMapByDate();

        return $this->mapByDate;
    }

    /**
     * 
     * @param string $date
     * @param int $eventId
     * @param string $maxDate
     * @return array
     */
    public function findTimeMap(string $date, int $eventId, string $maxDate = null) {
        $this->validateDate($date);
        $this->dtStart = new \DateTime($this->getFormatDate($date));
        if ($maxDate === null) {
            $this->dtEnd = new \DateTime($this->getFormatDate($date));
            $this->dtEnd->add(new \DateInterval('P7D'));
        } else {
            $this->validateDate($maxDate);
            $this->dtEnd = new \DateTime($this->getFormatDate($maxDate));
        }
        $this->eventId = $eventId;
        $this->sortArrayTimeMap();
        ksort($this->mapByDate);
        return $this->mapByDate;
    }

    /**
     * запуск сортировок для отрезка времени
     */
    private function sortArrayTimeMap() {
        $this->sortEventTimeTimeMap();
        $this->sortEventCustomTimeMap();
        $this->sortBidsTimeMap();
    }

    
    

    /**
     * сортируем массив времени событий
     * идея метода пакетной вставки отсортировоного недельного массива времени и событий а потом циклом вставлять каждую неделю( если бы потребоволось выводить все события а не конкретное  на отрезке)
     */
    private function sortEventTimeTimeMap() {
        $provider = $this->searchEventTimes($this->eventId);
        $events = $provider->getModels();
        $events = $events[0];
        $eventTimes = $events->eventTimes;
        // сортируем записи времени по неделям
        $arrayWeekTime = [];
        foreach ($events->eventTimes as $eventTimes) {
            $arrayWeekTime[$eventTimes->weekDay][$eventTimes->time] = null;
        }
        // прогоняем неделию и если есть записи запускаем цикл
        $week = 8;

        $countDay = date_diff($this->dtEnd, $this->dtStart)->days;
        // если отрезок меньше 7 дней не всю неделю прогоняем
        if ($countDay <= 7) {
            $week = $countDay + 1;
        }

        for ($i = 1; $i < $week; $i++) {
            if (isset($arrayWeekTime[$this->dtStart->format('N')])) {
                $this->cycleWeekEvent($this->dtStart->format('Y-m-d'), $events, $arrayWeekTime[$this->dtStart->format('N')]);
            }
            $this->dtStart->add(new \DateInterval('P1D'));
        }
    }

    /**
     * Прогоняем недели и делаем запись
     * @param string $date
     * @param \advance\models\Events $events
     * @param array $arrayTime
     */
    private function cycleWeekEvent(string $date, Events $events, array $arrayTime) {
        $dateStart = new \DateTime($date);
        while ($dateStart <= $this->dtEnd) {
            $this->writeMapEvent($dateStart->format('Y-m-d'), $events);
            $this->writeMapTimes($dateStart->format('Y-m-d'), $events->id, $arrayTime);
            $dateStart->add(new \DateInterval('P7D'));
        }
    }

    /**
     * записываем в массив события
     * @param string $date
     * @param \advance\models\Events $events 
     */
    private function writeMapEvent(string $date, Events $events) {
        $this->mapByDate[$date][$events->id]['event'] = $events;
    }

    /**
     * записываем в массив записи времени события
     * @param string $date
     * @param int $eventId
     * @param int $time
     * @param \advance\models\EventBids $bids
     */
    private function writeMapTime(string $date, int $eventId, int $time, EventBids $bids = null) {
        $this->mapByDate[$date][$eventId]['times'][$time] = $bids;
    }

    /**
     * записываем в массив записи времени
     * @param string $date
     * @param int $eventId
     * @param array $times
     */
    private function writeMapTimes(string $date, int $eventId, array $times) {

        $this->mapByDate[$date][$eventId]['times'] = $times;
    }

    /**
     * запуск сортировок для даты
     */
    private function sortArrayMapByDate() {
        $this->sortMapByDateTime();
        $this->sortMapByDateCustom();
        $this->sortMapByDateBids();
    }

    /**
     * записываем события по циклу
     */
    private function sortMapByDateTime() {
        $provider = $this->searchMapByDateTime($this->weekDayN);
        $model = $provider->getModels();
        foreach ($model as $events) {
            $this->writeMapEvent($this->useDate, $events);
            foreach ($events->eventTimes as $eventTimes) {
                $this->writeMapTime($this->useDate, $events->id, $eventTimes->time);
            }
        }
    }

    /**
     * сортируем custom события
     */
    private function sortMapByDateCustom() {
        $provider = $this->searchMapByDateCustom($this->useDate);
        $this->cycleEventCustom($provider);
        
    }

    /**
     * сортируем записи пользователей
     */
    private function sortMapByDateBids() {
        $provider = $this->searchMapByDateBids($this->useDate);
        $this->cycleEvetBids($provider);
    }

    /**
     * записываем события пользователей на отрезок времени
     */
    private function sortBidsTimeMap() {
        $provider = $this->searchEventBids($this->eventId);
        $this->cycleEvetBids($provider);
    }

    /**
     * записываем custom на отрезок времени
     */
    private function sortEventCustomTimeMap() {
        $provider = $this->searchEventCustom($this->eventId);
        $this->cycleEventCustom($provider);
    }
    
    /**
     * цикл для evetCustom
     * @param yii\data\ActiveDataProvider $provider
     */
    private function cycleEventCustom(ActiveDataProvider $provider) {
        $model = $provider->getModels();
        foreach ($model as $events) {
            foreach ($events->eventCustoms as $eventCustoms) {
                if (!empty($eventCustoms->eventCustomTimes)) {
                    $this->writeMapEvent($eventCustoms->date, $events);
                }
                foreach ($eventCustoms->eventCustomTimes as $eventCustomTimes) {
                    $this->writeMapTime($eventCustoms->date, $events->id, $eventCustomTimes->time);
                }
            }
        }
    }
    
    /**
     * цикл для evetBids
     * @param yii\data\ActiveDataProvider $provider
     */
    private function cycleEvetBids(ActiveDataProvider $provider) {
        $model = $provider->getModels();
        foreach ($model as $events) {
            foreach ($events->eventBids as $eventBids) {
                //если запись в цикле или custom была удалена или изменена строка то записываем на какое событие уже была запись
                if (!isset($this->mapByDate[$events->id]['events'])) {
                    $this->writeMapEvent($eventBids->date, $events);
                }
                $this->writeMapTime($eventBids->date, $events->id, $eventBids->time, $eventBids);
            }
        }
    }
    
    /**
     * проверяем на вадидность дату
     * @param string $date
     * @return boolean
     * @throws BadRequestHttpException
     */
    private function validateDate(string $date) {

        if (strtotime($date) === false) {
            throw new BadRequestHttpException('Не верный формат даты: "' . $date . '".');
        }
    }

    /**
     * Получить формат даты
     * @param string $date
     * @return string format YYYY-MM-DD
     */
    protected function getFormatDate(string $date) {
        $time = strtotime($date);
        return date('Y-m-d', $time);
    }

}

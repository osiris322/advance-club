<?php

namespace advance\models;

use yii\data\ActiveDataProvider;

/**
 * IncidentSearch represents the model behind the search form of `app\models\incident\Incident`.
 */
class EventsSearch extends Events
{
    public function searchMapByDateTime($weekDay)
    {
        $query = Events::find()
                ->joinWith(['eventTimes' => function($q) use ($weekDay) {
                                $q->andOnCondition(['eventTimes.weekDay' => $weekDay]);
                            }
                        ])
                ->where(['eventTimes.weekDay' => $weekDay]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $dataProvider;
    }
    
    public function searchMapByDateCustom($date)
    {
        $query = Events::find()
                ->joinWith(['eventCustoms' => function($q) use ($date) {
                                $q->andOnCondition(['eventCustom.date' => $date]);
                            }
                        ])
                ->with('eventCustoms.eventCustomTimes')
                ->where(['eventCustom.date' => $date]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $query->andFilterWhere([
            'eventCustom.date' => $date,
        ]);

        return $dataProvider;
    }
    
    public function searchMapByDateBids($date)
    {
        $query = Events::find()
                ->joinWith(['eventBids' => function($q) use ($date) {
                                $q->andOnCondition(['eventBids.date' => $date]);
                            }
                        ])
                ->where(['eventBids.date' => $date]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        

        return $dataProvider;
    }
    
    public function searchEventTimes($eventId)
    {
        $query = Events::find()
                ->joinWith(['eventTimes' => function($q) use ($eventId) {
                                $q->andOnCondition(['eventTimes.eventId' => $eventId]);
                            }
                        ])
                ->where(['events.id' => $eventId]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        

        return $dataProvider;
    }
    
    public function searchEventCustom($eventId)
    {
        $query = Events::find()
                ->joinWith(['eventCustoms' => function($q) use ($eventId) {
                                $q->andOnCondition(['eventCustom.eventId' => $eventId]);
                            }
                        ])
                ->joinWith('eventCustoms.eventCustomTimes')
                ->where(['events.id' => $eventId]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        

        return $dataProvider;
    }
    
    public function searchEventBids($eventId)
    {
        $query = Events::find()
                ->joinWith(['eventBids' => function($q) use ($eventId) {
                                $q->andOnCondition(['eventBids.eventId' => $eventId]);
                            }
                        ])
                ->where(['events.id' => $eventId]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        

        return $dataProvider;
    }
    
    
}



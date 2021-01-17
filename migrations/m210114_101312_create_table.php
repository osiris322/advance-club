<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%}}`.
 */
class m210114_101312_create_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = '';

        if (Yii::$app->db->driverName == 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%events}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text()->null(),
        ], $tableOptions);
        
        $this->createTable('{{%eventTimes}}', [
            'id' => $this->primaryKey(),
            'eventId' => $this->integer()->notNull(),
            'weekDay' => $this->integer()->notNull(),
            'time' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('idx-eventTimes-eventId', 'eventTimes', 'eventId');
        $this->addForeignKey('fk-eventTimes-eventId','eventTimes', 'eventId', 'events', 'id');
        //запрещаем ставить одно и то же событие в одинаковый час
        $this->createIndex('idx-eventTimes-eventId-weekDay-time', 'eventTimes', ['eventId','weekDay','time'], $unique=true);
        
        $this->createTable('{{%eventCustom}}', [
            'id' => $this->primaryKey(),
            'eventId' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('idx-eventCustom-eventId', 'eventCustom', 'eventId');
        $this->addForeignKey('fk-eventCustom-eventId','eventCustom', 'eventId', 'events', 'id');
        
        $this->createTable('{{%eventCustomTimes}}', [
            'id' => $this->primaryKey(),
            'customId' => $this->integer()->notNull(),
            'time' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('idx-eventCustomTimes-customId', 'eventCustomTimes', 'customId');
        $this->addForeignKey('fk-eventCustomTimes-customId','eventCustomTimes', 'customId', 'eventCustom', 'id', 'CASCADE' );
        //запрещаем ставить одно и то же событие в одинаковый час
        $this->createIndex('idx-eventCustomTimes-customId-time', 'eventCustomTimes', ['customId','time'], $unique=true);
        
        $this->createTable('{{%eventBids}}', [
            'id' => $this->primaryKey(),
            'eventId' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'time' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'contact' => $this->text()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('idx-eventBids-customId', 'eventBids', 'eventId');
        $this->addForeignKey('fk-eventBids-customId','eventBids', 'eventId', 'events', 'id');
        //запрещаем ставить одно и то же событие в одинаковый час
        $this->createIndex('idx-eventBids-eventId-date-time', 'eventBids', ['eventId','date','time'], $unique=true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%eventBids}}');
        $this->dropTable('{{%eventCustomTimes}}');
        $this->dropTable('{{%eventCustom}}');
        $this->dropTable('{{%eventTimes}}');
        $this->dropTable('{{%events}}');
    }
}

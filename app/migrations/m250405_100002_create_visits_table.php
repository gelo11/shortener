<?php

use yii\db\Migration;

class m250405_100002_create_visits_table extends Migration
{
    public function up()
    {
        $this->createTable('visits', [
            'id' => $this->primaryKey(),
            'link_id' => $this->integer()->notNull(),
            'user_agent' => $this->string(512),
            'visited_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-visits-link_id',
            'visits',
            'link_id',
            'links',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-visits-link_id', 'visits', 'link_id');
        $this->createIndex('idx-visits-visited_at', 'visits', 'visited_at');
    }

    public function down()
    {
        $this->dropTable('visits');
    }
}
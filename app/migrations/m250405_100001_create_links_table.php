<?php

use yii\db\Migration;

class m250405_100001_create_links_table extends Migration
{
    public function up()
    {
        $this->createTable('links', [
            'id' => $this->primaryKey(),
            'original_url' => $this->text()->notNull(),
            'short_code' => $this->string(10)
                ->notNull()
                ->unique()
                ->append('COLLATE utf8mb4_bin'),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-links-short_code', 'links', 'short_code');
    }

    public function down()
    {
        $this->dropTable('links');
    }
}
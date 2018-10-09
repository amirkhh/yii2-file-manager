<?php

use yii\db\Migration;

/**
 * Class m180927_194229_file
 */
class m180927_194229_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'mime_type' => $this->string(255)->notNull(),
            'extension' => $this->string(255)->notNull(),
            'hash_file' => $this->string(255)->notNull(),
            'hash_name' => $this->string(255)->notNull(),
            'size' => $this->integer(11)->notNull(),
            'created_at' => $this->integer(11)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('file');
    }
}

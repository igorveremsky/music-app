<?php

use yii\db\Migration;

/**
 * Class m180626_124632_create_files_tables
 */
class m180626_124632_create_files_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
	    }

	    $this->createTable('{{%images}}', [
		    'id' => $this->primaryKey(),
		    'file_src' => $this->string()->unique()->notNull(),
	    ], $tableOptions);

	    $this->createTable('{{%audiofiles}}', [
		    'id' => $this->primaryKey(),
		    'file_src' => $this->string()->unique()->notNull(),
	    ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropTable('{{%audiofiles}}');
	    $this->dropTable('{{%images}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `artists`.
 */
class m180626_140423_create_artists_table extends Migration
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

	    $this->createTable('{{%artists}}', [
		    'id' => $this->primaryKey(),
		    'name' => $this->string()->unique()->notNull(),
		    'type' => "ENUM('g', 's')",
		    'avatar_img_id' => $this->integer()->unique(),
	    ], $tableOptions);

	    $this->addForeignKey(
		    'fk-artists-avatar_img_id',
		    '{{%artists}}',
		    'avatar_img_id',
		    '{{%images}}',
		    'id',
		    'SET NULL',
		    'CASCADE'
	    );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%artists}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `albums`.
 */
class m180626_132950_create_albums_table extends Migration
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

	    $this->createTable('{{%albums}}', [
		    'id' => $this->primaryKey(),
		    'genre_id' => $this->integer(2),
		    'name' => $this->string()->unique()->notNull(),
		    'cover_img_id' => $this->integer(),
		    'year' => $this->smallInteger()->notNull(),
		    'records_name' => $this->string()->notNull(),
	    ], $tableOptions);

	    $this->addForeignKey(
		    'fk-albums-genre_id',
		    '{{%albums}}',
		    'genre_id',
		    '{{%genres}}',
		    'id',
		    'SET NULL',
		    'CASCADE'
	    );

	    $this->addForeignKey(
		    'fk-albums-cover_img_id',
		    '{{%albums}}',
		    'cover_img_id',
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
        $this->dropTable('{{%albums}}');
    }
}

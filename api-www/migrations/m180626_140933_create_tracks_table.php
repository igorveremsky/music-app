<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tracks`.
 */
class m180626_140933_create_tracks_table extends Migration
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

	    $this->createTable('{{%tracks}}', [
		    'id' => $this->primaryKey(),
		    'file_id' => $this->integer()->unique()->notNull(),
		    'name' => $this->string()->unique()->notNull(),
		    'album_id' => $this->integer(),
		    'album_number' => $this->tinyInteger()->defaultValue(1)->notNull(),
		    'is_explicit' => $this->boolean()->defaultValue(0)->notNull(),
	    ], $tableOptions);

	    $this->addForeignKey(
		    'fk-tracks-file_id',
		    '{{%tracks}}',
		    'file_id',
		    '{{%audiofiles}}',
		    'id',
		    'CASCADE',
		    'CASCADE'
	    );

	    $this->createIndex(
		    'idx-tracks-album',
		    '{{%tracks}}',
		    ['album_id', 'album_number'],
		    true
	    );

	    $this->addForeignKey(
		    'fk-tracks-album_id',
		    '{{%tracks}}',
		    'album_id',
		    '{{%albums}}',
		    'id',
		    'SET NULL',
		    'CASCADE'
	    );

	    $this->createTable('{{%track_artists}}', [
		    'id' => $this->primaryKey(),
		    'track_id' => $this->integer()->notNull(),
		    'artist_id' => $this->integer()->notNull(),
	    ], $tableOptions);

	    $this->addForeignKey(
		    'fk-track_artists-track_id',
		    '{{%track_artists}}',
		    'track_id',
		    '{{%tracks}}',
		    'id',
		    'CASCADE',
		    'CASCADE'
	    );

	    $this->addForeignKey(
		    'fk-track_artists-artist_id',
		    '{{%track_artists}}',
		    'artist_id',
		    '{{%artists}}',
		    'id',
		    'CASCADE',
		    'CASCADE'
	    );

	    $this->createIndex(
		    'idx-track_artists-album',
		    '{{%track_artists}}',
		    ['track_id', 'artist_id'],
		    true
	    );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%track_artists}}');
        $this->dropTable('{{%tracks}}');
    }
}

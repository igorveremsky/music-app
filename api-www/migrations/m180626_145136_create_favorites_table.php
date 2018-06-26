<?php

use yii\db\Migration;

/**
 * Handles the creation of table `favorites`.
 */
class m180626_145136_create_favorites_table extends Migration
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

		$this->createTable('{{%favorites}}', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'model_type' => "ENUM('tr', 'ar', 'al') NOT NULL",
			'model_id' => $this->integer()->notNull(),
		], $tableOptions);

		$this->createIndex(
			'idx-favorites',
			'{{%favorites}}',
			['user_id', 'model_type', 'model_id'],
			true
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{%favorites}}');
	}
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%track_artists}}".
 *
 * @property int $id
 * @property int $track_id
 * @property int $artist_id
 */
class TrackArtist extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%track_artists}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['track_id', 'artist_id'], 'required'],
			[['track_id', 'artist_id'], 'integer']
		];
	}
}

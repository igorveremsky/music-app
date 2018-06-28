<?php

namespace app\models;

use app\behaviors\UploadBehavior;
use app\modules\v1\behaviors\FileActiveRecordBehavior;
use app\modules\v1\traits\FavoriteModelTrait;
use hiqdev\hiart\ActiveRecord;
use voskobovich\linker\LinkerBehavior;
use Yii;

/**
 * This is the model class for table "{{%tracks}}".
 *
 * @property int $id
 * @property int $file_id
 * @property string $audio_file_src
 * @property string $name
 * @property int $album_id
 * @property int $album_number
 * @property int $is_explicit
 *
 * @property array $audiofile
 * @property array $artists
 * @property array $album
 */
class Track extends ActiveRecord {
	const FAVORITE_TYPE = 'tr';

	public $album;
	public $artists;
	public $audiofile;
	public $is_favorite;

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			[
				'class' => UploadBehavior::class,
				'attribute' => 'audio_file_src',
				'path' => Yii::getAlias('@webroot/upload/audios/'),
				'url' => Yii::getAlias('@web/upload/audios/'),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['id', 'integer', 'min' => 1],
			[['name', 'album_number'], 'required'],
			[['album_id', 'album_number', 'is_explicit'], 'integer'],
			['is_explicit', 'in', 'range' => [1, 0]],
			[['name'], 'string', 'max' => 255],
			['artist_ids', 'each', 'rule' => ['integer']],
			['audio_file_src', 'file', 'extensions' => ['mp3', 'm4a', 'aac', 'oga']],
			['audio_file_src', 'required'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function afterFind() {
		parent::afterFind();

		if (!empty($this->audiofile)) {
			$this->setOldAttribute('audio_file_src', $this->audiofile['file_src']);
			$this->audio_file_src = $this->audiofile['file_src'];
		}
	}
}

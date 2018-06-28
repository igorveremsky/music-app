<?php

namespace app\modules\v1\models;

use app\modules\v1\behaviors\FileActiveRecordBehavior;
use app\modules\v1\traits\FavoriteModelTrait;
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
 * @property Artist[] $artists
 * @property Album $album
 * @property Audiofile $audio_file
 */
class Track extends \yii\db\ActiveRecord {
	use FavoriteModelTrait;
	const FAVORITE_TYPE = 'tr';

	public $audio_file_src;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%tracks}}';
	}

	/**
	 * @inheritdoc
	 */
	public function fields() {
		$fields = parent::fields();

		$fields['artists'] = 'artists';

		unset($fields['album_id'], $fields['file_id']);

		$fields['audiofile'] = 'audiofile';
		$fields['album'] = 'album';
		$fields['is_favorite'] = function ($model) {
			/* @var $model FavoriteModelTrait */
			return $model->getIsFavorite();
		};

		return $fields;
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'fileActiveRecord' => [
				'class' => FileActiveRecordBehavior::class,
				'fileSrcAttribute' => 'audio_file_src',
				'fileIdAttribute' => 'file_id',
				'fileClass' => Audiofile::class,
			],
			[
				'class' => LinkerBehavior::class,
				'relations' => [
					'artist_ids' => 'artists',
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['is_explicit', 'default', 'value' => 0],
			[['name'], 'required'],
			[['file_id', 'album_id', 'album_number', 'is_explicit'], 'integer'],
			['is_explicit', 'in', 'range' => [1, 0]],
			[['name'], 'string', 'max' => 255],
			[['file_id'], 'unique'],
			[['name'], 'unique'],
			[['album_id', 'album_number'], 'unique', 'targetAttribute' => ['album_id', 'album_number']],
			[
				['album_id'],
				'exist',
				'skipOnError' => true,
				'targetClass' => Album::class,
				'targetAttribute' => ['album_id' => 'id']
			],
			['artist_ids', 'each', 'rule' => ['integer']],
			[
				'artist_ids',
				'each',
				'rule' => [
					'exist',
					'skipOnError' => true,
					'targetClass' => Artist::class,
					'targetAttribute' => ['artist_ids' => 'id']
				]
			]
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArtists() {
		return $this->hasMany(Artist::class, ['id' => 'artist_id'])
		            ->viaTable('{{%track_artists}}', ['track_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAlbum() {
		return $this->hasOne(Album::class, ['id' => 'album_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAudiofile() {
		/* @var $this FileActiveRecordBehavior */
		return $this->getFile();
	}
}

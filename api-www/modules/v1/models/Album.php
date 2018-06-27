<?php

namespace app\modules\v1\models;

use app\modules\v1\behaviors\FileActiveRecordBehavior;
use app\modules\v1\helpers\FileHelper;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "{{%albums}}".
 *
 * @property int $id
 * @property int $genre_id
 * @property string $name
 * @property int $cover_img_id
 * @property string $cover_img_src
 * @property int $year
 * @property string $records_name
 * @property string $genre
 */
class Album extends \yii\db\ActiveRecord {
	public $cover_img_src;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%albums}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'fileActiveRecord' => [
				'class' => FileActiveRecordBehavior::class,
				'fileSrcAttribute' => 'cover_img_src',
				'fileIdAttribute' => 'cover_img_id',
				'fileClass' => Image::class,
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function fields() {
		$fields = parent::fields();

		unset($fields['genre_id'], $fields['cover_img_id']);

		$fields['cover_img'] = 'coverImg';
		$fields['genre'] = 'genre';

		return $fields;
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['year', 'default', 'value' => date('Y')],
			[['genre_id', 'cover_img_id', 'year'], 'integer'],
			[['name', 'year', 'records_name'], 'required'],
			[['name', 'records_name'], 'string', 'max' => 255],
			[['name'], 'unique'],
			[
				['genre_id'],
				'exist',
				'skipOnError' => true,
				'targetClass' => Genre::class,
				'targetAttribute' => ['genre_id' => 'id']
			],
		];
	}

	/**
	 * @return mixed
	 */
	public function getCoverImg() {
		/* @var $this FileActiveRecordBehavior */
		return $this->getFile();
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGenre() {
		return $this->hasOne(Genre::class, ['id' => 'genre_id']);
	}
}

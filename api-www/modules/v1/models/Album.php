<?php

namespace app\modules\v1\models;

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
			[
				['cover_img_id'],
				'exist',
				'skipOnError' => true,
				'targetClass' => Image::class,
				'targetAttribute' => ['cover_img_id' => 'id']
			],
			[['cover_img_src'], 'safe'],
			[['cover_img_src'], 'string'],
			[['cover_img_src'], 'existFile'],
		];
	}

	/**
	 * Check is file exist
	 */
	public function existFile() {
		if (!FileHelper::isExistFromSrc($this->cover_img_src)) {
			$this->addError('cover_img_src', 'Invalid "cover_img_src". File not found.');
		}
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception|\Throwable
	 */
	public function beforeSave($insert) {
		if (!parent::beforeSave($insert)) {
			return false;
		}

		if (!empty($this->cover_img_src)) {
			$transaction = Yii::$app->db->beginTransaction();

			try {
				$image = Image::initializeFromSrc($this->cover_img_src);

				if ($image->isNewRecord && !$image->save()) {
					$this->addError('cover_img_src', 'Error during save or validate image');

					return false;
				}

				if (!$insert && $this->cover_img_id !== $image->id) {
					$image::deleteAll(['id' => $this->cover_img_id]);
				}

				$this->cover_img_id = $image->id;

				$transaction->commit();
			} catch (\Exception $e) {
				$transaction->rollBack();
				throw $e;
			} catch (\Throwable $e) {
				$transaction->rollBack();
				throw $e;
			}
		}

		return true;
	}

	/**
	 * @param bool $insert
	 *
	 * @return bool
	 * @throws ServerErrorHttpException
	 */
	public function beforeDelete() {
		if (!parent::beforeDelete()) {
			return false;
		}

		if (!empty($this->cover_img_id)) {
			Image::deleteAll(['id' => $this->cover_img_id]);
		}

		return true;
	}

	/**
	 * @return \yii\db\ActiveQuery|Image
	 */
	public function getCoverImg() {
		return $this->hasOne(Image::class, ['id' => 'cover_img_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGenre() {
		return $this->hasOne(Genre::class, ['id' => 'genre_id']);
	}
}

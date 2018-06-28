<?php

namespace app\modules\v1\models;

use app\modules\v1\behaviors\FavoriteModelBehavior;
use app\modules\v1\behaviors\FileActiveRecordBehavior;
use Yii;

/**
 * This is the model class for table "{{%artists}}".
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $avatar_img_id
 * @property string $avatar_img_src
 *
 * @property Image $avatarImg
 */
class Artist extends \yii\db\ActiveRecord {
	const FAVORITE_TYPE = 'ar';

	const TYPE_GROUP = 'g';
	const TYPE_SINGLE = 's';

	public $avatar_img_src;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%artists}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'fileActiveRecord' => [
				'class' => FileActiveRecordBehavior::class,
				'fileSrcAttribute' => 'avatar_img_src',
				'fileIdAttribute' => 'avatar_img_id',
				'fileClass' => Image::class,
			],
			'favoriteModel' => [
				'class' => FavoriteModelBehavior::class,
				'favoriteType' => self::FAVORITE_TYPE
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function fields() {
		$fields = parent::fields();

		unset($fields['avatar_img_id']);

		$fields['avatar_img'] = 'avatarImg';
		$fields['is_favorite'] = function ($model) {
			/* @var $model FavoriteModelBehavior */
			return $model->getIsFavorite();
		};

		return $fields;
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name', 'type'], 'required'],
			[['type'], 'string'],
			[['type'], 'in', 'range' => [self::TYPE_GROUP, self::TYPE_SINGLE]],
			[['avatar_img_id'], 'integer'],
			[['name'], 'string', 'max' => 255],
			[['name'], 'unique'],
			[['avatar_img_id'], 'unique']
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAvatarImg() {
		/* @var $this FileActiveRecordBehavior */
		return $this->getFile();
	}
}

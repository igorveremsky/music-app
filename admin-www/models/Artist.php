<?php

namespace app\models;

use hiqdev\hiart\ActiveRecord;
use app\behaviors\UploadBehavior;
use yii\helpers\Url;

/**
 * Class Artist
 * @package app\models
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $avatar_img_src
 * @property array $avatar_img
 */
class Artist extends ActiveRecord {
	const TYPE_GROUP = 'g';
	const TYPE_SINGLE = 's';

	public $avatar_img;

	/**
	 * @inheritdoc
	 */
	function behaviors() {
		return [
			[
				'class' => UploadBehavior::class,
				'attribute' => 'avatar_img_src',
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['id', 'integer', 'min' => 1],
			[['name', 'type'], 'required'],
			[['type'], 'string'],
			[['type'], 'in', 'range' => [self::TYPE_GROUP, self::TYPE_SINGLE]],
			[['name'], 'string', 'max' => 255],
			['avatar_img_src', 'file', 'extensions' => ['jpg', 'png', 'gif']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function afterFind() {
		parent::afterFind();

		if (!empty($this->avatar_img)) {
			$this->setOldAttribute('avatar_img_src', $this->avatar_img['file_src']);
			$this->avatar_img_src = $this->avatar_img['file_src'];
		}
	}

	/**
	 * Get artist type label options
	 *
	 * @return array
	 */
	public static function getTypeLabelOptions() {
		return [
			self::TYPE_GROUP => 'Group',
			self::TYPE_SINGLE => 'Single',
		];
	}
}

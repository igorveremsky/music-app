<?php

namespace app\models;

use hiqdev\hiart\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Artist
 * @package app\models
 *
 * @property string $model_type
 * @property array $model
 */
class Favorite extends ActiveRecord {
	public $model;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['id', 'integer', 'min' => 1],
			[['model_type', 'model_id'], 'required'],
			['model_type', 'in', 'range' => array_keys($this->getModelTypeClassOptions())],
			[['user_id', 'model_id'], 'integer'],
		];
	}

	/**
	 * Get favorite model class name
	 *
	 * @return ActiveRecord|null
	 */
	protected function getModelClass() {
		return ArrayHelper::getValue($this->getModelTypeClassOptions(), $this->model_type);
	}

	/**
	 * Get favorite model type class options
	 *
	 * @return array
	 */
	protected function getModelTypeClassOptions() {
		return [
			Album::FAVORITE_TYPE => Album::class,
			Artist::FAVORITE_TYPE => Artist::class,
			Track::FAVORITE_TYPE => Track::class,
		];
	}

	/**
	 * Get favorite model type label options
	 *
	 * @return array
	 */
	public static function getModelTypeLabelOptions() {
		return [
			Album::FAVORITE_TYPE => 'Album',
			Artist::FAVORITE_TYPE => 'Artist',
			Track::FAVORITE_TYPE => 'Track',
		];
	}
}

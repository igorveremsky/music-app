<?php

namespace app\models;

use hiqdev\hiart\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Artist
 * @package app\models
 *
 * @property int $id
 * @property int $user_id
 * @property string $model_type
 * @property int $model_id
 */
class Favorite extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['id', 'integer', 'min' => 1],
			[['model_type', 'model_id'], 'required'],
			['model_type', 'in', 'range' => array_keys($this->getModelTypeClassOptions())],
			[['user_id', 'model_id'], 'integer'],
			[['model_type', 'model_id'], 'checkModelExist'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function beforeValidate() {
		$this->user_id = Yii::$app->getUser()->getId();

		return parent::beforeValidate();
	}

	/**
	 * Check is model with such type and id exist
	 *
	 * @param $attributes
	 */
	public function checkModelExist($attributes) {
		$modelClass = ArrayHelper::getValue($this->getModelTypeClassOptions(), $this->model_type);

		if (!empty($modelClass)) {
			/* @var $modelClass ActiveRecord */
			if (!$modelClass::find()->where(['id' => $this->model_id])->exists()) {
				$this->addError($attributes, 'Model not exist');
			};
		}
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
}

<?php

namespace app\modules\v1\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%favorites}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $model_type
 * @property int $model_id
 */
class Favorite extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%favorites}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			[
				'class' => BlameableBehavior::className(),
				'createdByAttribute' => 'user_id',
				'updatedByAttribute' => false,
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function fields() {
		$fields = parent::fields();

		unset($fields['model_id'],$fields['user_id']);

		$fields['model'] = 'model';

		return $fields;
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['model_type', 'model_id'], 'required'],
			['model_type', 'in', 'range' => array_keys($this->getModelTypeClassOptions())],
			[['user_id', 'model_id'], 'integer'],
			[['model_type'], 'string'],
			[
				['user_id', 'model_type', 'model_id'],
				'unique',
				'targetAttribute' => ['user_id', 'model_type', 'model_id']
			],
			[['model_id'], 'checkModelExist'],
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
		$modelClass = $this->getModelClass();

		if (!empty($modelClass)) {
			/* @var $modelClass ActiveRecord */
			if (!$modelClass::find()->where(['id' => $this->model_id])->exists()) {
				$this->addError($attributes, 'Model not exist');
			};
		}
	}

	/**
	 * Check is model with such type and id exist
	 *
	 * @return null
	 */
	public function getModel() {
		$modelClass = $this->getModelClass();

		if (!empty($modelClass)) {
			return $modelClass::find()->where(['id' => $this->model_id])->one();
		}

		return null;
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
}

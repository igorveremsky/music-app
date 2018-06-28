<?php

namespace app\modules\v1\behaviors;

use app\modules\v1\helpers\FileHelper;
use app\modules\v1\interfaces\FileInterface;
use app\modules\v1\models\Favorite;
use app\modules\v1\models\Image;
use app\modules\v1\validators\ExistFileValidator;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\validators\Validator;
use Yii;

/**
 * Class FavoriteModelBehavior
 * @package app\modules\v1\behaviors
 */
class FavoriteModelBehavior extends Behavior {
	public $favoriteType;

	/**
	 * @inheritdoc
	 *
	 * @throws InvalidConfigException
	 */
	public function attach($owner) {
		parent::attach($owner);

		if ($this->favoriteType === null) {
			throw new InvalidConfigException('The "type" property must be set.');
		}
	}

	/**
	 * @inheritdoc
	 */
	public function events() {
		return [
			BaseActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
		];
	}

	/**
	 * This method is invoked before deleting a record.
	 */
	public function beforeDelete() {
		Favorite::deleteAll(['model_type' => $this->favoriteType, 'model_id' => $this->owner->id]);
	}

	/**
	 * @return bool
	 */
	public function getIsFavorite() {
		return Favorite::find()->where([
			'user_id' => Yii::$app->getUser()->getId(),
			'model_type' => $this->favoriteType,
			'model_id' => $this->owner->id
		])->exists();
	}
}
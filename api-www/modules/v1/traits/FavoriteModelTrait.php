<?php

namespace app\modules\v1\traits;

use app\modules\v1\models\Favorite;
use Yii;

/**
 * Class FileBehavior
 * @package app\modules\v1\behaviors
 */
trait FavoriteModelTrait {
	public $favoriteType = self::FAVORITE_TYPE;

	/**
	 * @return bool
	 */
	public function getIsFavorite() {
		return Favorite::find()->where([
			'user_id' => Yii::$app->getUser()->getId(),
			'model_type' => $this->favoriteType,
			'model_id' => $this->id
		])->exists();
	}
}
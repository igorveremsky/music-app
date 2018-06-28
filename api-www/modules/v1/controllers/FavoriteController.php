<?php

namespace app\modules\v1\controllers;

use app\modules\v1\models\Favorite;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class FavoriteController
 * @package app\modules\v1\controllers
 */
class FavoriteController extends DefaultController {
	public $modelClass = Favorite::class;

	/**
	 * @return array
	 */
	public function actions()
	{
		return array_merge(
			parent::actions(),
			[
				'index' => [
					'class' => 'yii\rest\IndexAction',
					'modelClass' => $this->modelClass,
					'checkAccess' => [$this, 'checkAccess'],
					'prepareDataProvider' => function ($action) {
						/* @var $model Favorite */
						$model = $this->modelClass;
						$query = $model::find();

						$where['user_id'] = Yii::$app->user->getId();
						if (!empty($queryParam = Yii::$app->request->getQueryParam('model_id'))) {
							$where['model_id'] = $queryParam;
						}
						if (!empty($queryParam = Yii::$app->request->getQueryParam('model_type'))) {
							$where['model_type'] = $queryParam;
						}

						$query->where($where);

						if ($query->count() == 1 && count($where) === 3) {
							return $query->one();
						}

						$dataProvider = new ActiveDataProvider(['query' => $query]);

						return $dataProvider;
					}
				]
			]
		);
	}
}
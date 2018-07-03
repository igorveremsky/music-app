<?php

namespace app\modules\v1\controllers;

use app\modules\v1\models\elastic\GenreElastic;
use app\modules\v1\models\Genre;
use Yii;

/**
 * Class GenreController
 * @package app\modules\v1\controllers
 */
class GenreController extends DefaultController {
	public $modelClass = Genre::class;

	/**
	 * Search genres by ElasticSearch
	 *
	 * @return GenreElastic[]|array
	 */
	public function actionSearch() {
		$query = (string) Yii::$app->request->get('q');
		$result = GenreElastic::find()->query(['match' => ['name' => $query]])->all();

		return $result;
	}
}
<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Class DefaultController
 * @package app\modules\v1\controllers
 */
class DefaultController extends Controller {
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		unset($behaviors['contentNegotiator']['formats']['application/xml']);

		return $behaviors;
	}

	/**
	 * @return array
	 */
	public function actionIndex() {
		return ['foo'=>'bar'];
	}
}
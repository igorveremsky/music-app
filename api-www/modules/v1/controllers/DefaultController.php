<?php

namespace app\modules\v1\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\rest\Controller;

/**
 * Class DefaultController
 * @package app\modules\v1\controllers
 */
class DefaultController extends ActiveController {
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		unset($behaviors['contentNegotiator']['formats']['application/xml']);

		$behaviors['authenticator'] = [
			'class' => CompositeAuth::className(),
			'authMethods' => [
				HttpBearerAuth::className(),
				QueryParamAuth::className(),
			],
		];

		return $behaviors;
	}

	/**
	 * @return array
	 */
	public function actionIndex() {
		return ['foo'=>'bar'];
	}
}
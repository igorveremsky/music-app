<?php

namespace app\controllers;

use app\models\Favorite;
use hiqdev\hiart\ActiveDataProvider;
use hiqdev\hiart\ActiveRecord;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FavoriteController implements the RD actions for Favorite model.
 */
class FavoriteController extends Controller {
	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all models.
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->render('index', [
			'dataProvider' => new ActiveDataProvider([
				'query' => Favorite::find(),
				'sort' => false
			]),
		]);
	}

	/**
	 * Deletes an existing model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param $id
	 *
	 * @return ActiveRecord|null
	 * @throws NotFoundHttpException
	 */
	protected function findModel($id) {
		if (($model = Favorite::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
<?php

namespace app\controllers;

use hiqdev\hiart\ActiveDataProvider;
use hiqdev\hiart\ResponseErrorException;
use Yii;
use app\models\Genre;
use app\models\GenreSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GenreController implements the CRUD actions for Genre model.
 */
class GenreController extends Controller {
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
	 * Lists all Genre models.
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->render('index', [
			'dataProvider' => new ActiveDataProvider([
				'query' => Genre::find(),
			]),
		]);
	}

	/**
	 * Displays a single Genre model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Genre model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Genre();

		if ($model->load(Yii::$app->request->post())) {
			try {
				$model->save();
			} catch (ResponseErrorException $e) {
				if ($e->getResponse()->getStatusCode() == 422) {
					foreach ($e->getResponse()->getData() as $validationError) {
						$model->addError($validationError['field'], $validationError['message']);
					}

					return $this->render('create', [
						'model' => $model,
					]);
				}
			}

			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Genre model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post())) {
			try {
				$model->save();
			} catch (ResponseErrorException $e) {
				if ($e->getResponse()->getStatusCode() == 422) {
					foreach ($e->getResponse()->getData() as $validationError) {
						$model->addError($validationError['field'], $validationError['message']);
					}

					return $this->render('update', [
						'model' => $model,
					]);
				}
			}

			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Genre model.
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
	 * Finds the Genre model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Genre the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Genre::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}

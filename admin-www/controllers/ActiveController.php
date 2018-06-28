<?php

namespace app\controllers;

use app\models\Favorite;
use hiqdev\hiart\ActiveDataProvider;
use hiqdev\hiart\ActiveRecord;
use hiqdev\hiart\ResponseErrorException;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ActiveController implements the CRUD actions for model.
 */
class ActiveController extends Controller {
	/* @var ActiveRecord */
	public $modelClass;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if ($this->modelClass === null) {
			throw new InvalidConfigException('The "modelClass" property must be set.');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
					'favorite' => ['POST'],
					'unfavorite' => ['POST'],
				],
			],
		];
	}

	/**
	 * Make model favorite
	 *
	 * @param $id
	 *
	 * @return string
	 * @throws ResponseErrorException
	 */
	public function actionFavorite($id) {
		$modelClass = $this->modelClass;
		if (defined("$modelClass::FAVORITE_TYPE")) {
			try {
				$model = new Favorite();
				$model->model_id = $id;
				$model->model_type = $modelClass::FAVORITE_TYPE;

				$model->save();
			} catch (ResponseErrorException $e) {
				throw $e;
			}
		}

		return $this->redirect(['index']);
	}

	/**
	 * Make model unfavorite
	 *
	 * @param $id
	 *
	 * @return string
	 * @throws ResponseErrorException
	 */
	public function actionUnfavorite($id) {
		$modelClass = $this->modelClass;
		if (defined("$modelClass::FAVORITE_TYPE")) {
			try {
				$model = Favorite::find()->where(['model_id' => $id, 'model_type' => $modelClass::FAVORITE_TYPE])->one();

				$model->delete();
			} catch (ResponseErrorException $e) {
				throw $e;
			}
		}

		return $this->redirect(['index']);
	}

	/**
	 * Lists all models.
	 * @return mixed
	 */
	public function actionIndex() {
		$modelClass = $this->modelClass;
		return $this->render('index', [
			'dataProvider' => new ActiveDataProvider([
				'query' => $modelClass::find(),
				'sort' => false
			]),
		]);
	}

	/**
	 * Displays a single model.
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$modelClass = $this->modelClass;
		/* @var $model ActiveRecord */
		$model = new $modelClass();

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
				} else {
					throw $e;
				}
			}

			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing model.
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
				} else {
					throw $e;
				}
			}

			return $this->redirect(['view', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
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
		$modelClass = $this->modelClass;
		if (($model = $modelClass::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}

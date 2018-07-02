<?php

namespace tests\models;

use app\models\User;
use app\modules\v1\models\Album;
use app\modules\v1\models\Artist;
use app\modules\v1\models\Favorite;
use app\modules\v1\models\Image;
use app\modules\v1\models\Track;
use yii\db\ActiveRecord;

/**
 * Class FavoriteTest
 * @package tests\models
 *
 * @property \UnitTester $tester
 * @property ActiveRecord $modelClass
 */
class FavoriteTest extends \Codeception\Test\Unit {
	protected $modelClass = Favorite::class;
	protected $initData = [
		'model_type' => 'al'
	];
	protected $forSaveData = [
		'model_type' => 'al',
		'model_id' => 2
	];

	protected $id;

	function _before() {
		\Yii::$app->user->login(new User([
			'id' => '100',
			'username' => 'admin',
			'password' => 'admin',
			'authKey' => 'test100key',
			'accessToken' => '100-token',
		]));
		$relativeId = $this->tester->haveRecord(Album::class, [
			'name' => 'favorite album',
			'year' => 2018,
			'records_name' => 'records'
		]);
		$this->initData['model_id'] = $relativeId;
		$this->id = $this->tester->haveRecord($this->modelClass, $this->initData);
	}

	public function testValidation() {
		/* @var $model Favorite */
		$model = new $this->modelClass;

		$model->model_type = null;
		$this->tester->assertFalse($model->validate('model_type'));

		$model->model_type = 'album';
		$this->tester->assertFalse($model->validate('model_type'));

		$model->model_type = Track::FAVORITE_TYPE;
		$this->tester->assertTrue($model->validate('model_type'));
		$model->model_type = Album::FAVORITE_TYPE;
		$this->tester->assertTrue($model->validate('model_type'));
		$model->model_type = Track::FAVORITE_TYPE;
		$this->tester->assertTrue($model->validate('model_type'));

		$model->model_id = 'test';
		$this->tester->assertFalse($model->validate('model_id'));
	}

	/**
	 * @depends testValidation
	 */
	public function testCreate() {
		/* @var $model ActiveRecord */
		$model = new $this->modelClass();
		$this->setModelAttributes($model, $this->forSaveData);
		$model->save(false);
		$this->tester->seeRecord($this->modelClass, $this->forSaveData);
	}

	public function testDelete() {
		$model = $this->modelClass::findOne($this->id);
		$model->delete();
		$this->tester->dontSeeRecord($this->modelClass, $this->initData);
	}

	protected function setModelAttributes(ActiveRecord $model, $data) {
		foreach ($data as $attribute => $value) {
			$model->$attribute = $value;
		}
	}
}
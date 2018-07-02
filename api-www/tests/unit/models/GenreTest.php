<?php

namespace tests\models;

use app\modules\v1\models\Genre;
use yii\db\ActiveRecord;

/**
 * Class GenreTest
 * @package tests\api\models
 *
 * @property \UnitTester $tester
 * @property ActiveRecord $modelClass
 */
class GenreTest extends \Codeception\Test\Unit {
	protected $modelClass = Genre::class;
	protected $initData = ['name' => 'hip-hop'];
	protected $forSaveData = ['name' => 'rock'];
	protected $forUpdateData = ['name' => 'rap'];

	protected $id;

	function _before()
	{
		// preparing a user, inserting user record to database
		$this->id = $this->tester->haveRecord($this->modelClass, $this->initData);
	}

	public function testValidation() {
		/* @var $model ActiveRecord */
		$model = new $this->modelClass;

		$model->name = null;
		$this->tester->assertFalse($model->validate('name'));

		$model->name = 'test';
		$this->tester->assertTrue($model->validate('name'));
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

	public function testUpdate() {
		$model = $this->modelClass::findOne($this->id);
		$this->tester->assertNotNull($model);
		$this->setModelAttributes($model, $this->forUpdateData);
		$model->save();
		$updatedData = array_merge(['id' => $this->id], $this->forUpdateData);
		$this->tester->seeRecord($this->modelClass, $updatedData);
		$this->tester->dontSeeRecord($this->modelClass, $this->initData);
	}

	public function testDelete() {
		$model = $this->modelClass::findOne($this->id);
		$this->tester->assertNotNull($model);
		$model->delete();
		$deletedData = array_merge(['id' => $this->id], $this->forUpdateData);
		$this->tester->dontSeeRecord($this->modelClass, $deletedData);
	}

	protected function setModelAttributes(ActiveRecord $model, $data) {
		foreach ($data as $attribute => $value) {
			$model->$attribute = $value;
		}
	}
}
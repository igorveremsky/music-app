<?php

namespace tests\models;

use app\modules\v1\models\Genre;
use yii\db\ActiveRecord;

/**
 * Class GenreTest
 * @package tests\api\models
 *
 * @property \UnitTester $tester
 */
class GenreTest extends \Codeception\Test\Unit {
	protected $initData = ['name' => 'hip-hop'];
	protected $forSaveData = ['name' => 'rock'];
	protected $forUpdateData = ['name' => 'rap'];

	protected $genreId;

	function _before()
	{
		// preparing a user, inserting user record to database
		$this->genreId = $this->tester->haveRecord(Genre::class, $this->initData);
	}

	public function testValidation() {
		$model = new Genre();

		$model->name = null;
		$this->tester->assertFalse($model->validate('name'));

		$model->name = 'test';
		$this->tester->assertTrue($model->validate('name'));
	}

	/**
	 * @depends testValidation
	 */
	public function testCreate() {
		$model = new Genre();
		$this->setModelAttributes($model, $this->forSaveData);
		$model->save(false);
		$this->tester->seeRecord(Genre::class, $this->forSaveData);
	}

	public function testUpdate() {
		$model = Genre::findOne($this->genreId);
		$this->tester->assertNotNull($model);
		$this->setModelAttributes($model, $this->forUpdateData);
		$model->save();
		$updatedData = array_merge(['id' => $this->genreId], $this->forUpdateData);
		$this->tester->seeRecord(Genre::class, $updatedData);
		$this->tester->dontSeeRecord(Genre::class, $this->initData);
	}

	public function testDelete() {
		$model = Genre::findOne($this->genreId);
		$this->tester->assertNotNull($model);
		$model->delete();
		$deletedData = array_merge(['id' => $this->genreId], $this->forUpdateData);
		$this->tester->dontSeeRecord(Genre::class, $deletedData);
	}

	protected function setModelAttributes(ActiveRecord $model, $data) {
		foreach ($data as $attribute => $value) {
			$model->setAttribute($attribute, $value);
		}
	}
}
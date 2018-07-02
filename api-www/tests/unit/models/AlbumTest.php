<?php

namespace tests\models;

use app\modules\v1\behaviors\FileActiveRecordBehavior;
use app\modules\v1\models\Album;
use app\modules\v1\models\Image;
use yii\db\ActiveRecord;

/**
 * Class AlbumTest
 * @package tests\models
 *
 * @property \UnitTester $tester
 * @property ActiveRecord $modelClass
 */
class AlbumTest extends \Codeception\Test\Unit {
	protected $modelClass = Album::class;
	protected $initData = [
		'name' => 'album',
		'year' => 2018,
		'cover_img_src' => 'http://reynoldsandreyner.com/wp-content/uploads/chernigivske-website-avatars-01v-675x645.jpg',
		'records_name' => 'records name'
	];
	protected $forSaveData = [
		'name' => 'new album',
		'year' => 2018,
		'cover_img_src' => 'http://reynoldsandreyner.com/wp-content/uploads/the-book-2018-03-updated.jpg',
		'records_name' => 'new records name'
	];
	protected $forUpdateData = [
		'name' => 'update album',
		'year' => 2017,
		'cover_img_src' => 'http://reynoldsandreyner.com/wp-content/uploads/the-book-2018-04-updated.jpg',
		'records_name' => 'update records name'
	];

	protected $id;

	function _before() {
		// preparing a user, inserting user record to database
		$this->id = $this->tester->haveRecord($this->modelClass, $this->initData);
	}

	public function testValidation() {
		/* @var $model Album */
		$model = new $this->modelClass;

		$model->name = null;
		$this->tester->assertFalse($model->validate('name'));

		$model->name = 'album';
		$this->tester->assertFalse($model->validate('name'));

		$model->name = 'test';
		$this->tester->assertTrue($model->validate('name'));

		$model->genre_id = 'test';
		$this->tester->assertFalse($model->validate('genre_id'));

		$model->year = 'test';
		$this->tester->assertFalse($model->validate('year'));

		$model->year = 2018;
		$this->tester->assertTrue($model->validate('year'));

		$model->cover_img_src = 'test';
		$this->tester->assertTrue($model->validate('cover_img_src'));

		$model->setScenario(FileActiveRecordBehavior::SCENARIO_CHECK_FILE_EXIST);
		$model->cover_img_src = 'test';
		$this->tester->assertFalse($model->validate('cover_img_src'));

		$model->cover_img_src = 'http://reynoldsandreyner.com/wp-content/uploads/the-book-2018-05-updated-1350x1290.jpg';
		$this->tester->assertTrue($model->validate('cover_img_src'));

		$model->records_name = null;
		$this->tester->assertFalse($model->validate('records_name'));

		$model->records_name = 'test';
		$this->tester->assertTrue($model->validate('records_name'));
	}

	/**
	 * @depends testValidation
	 */
	public function testCreate() {
		/* @var $model ActiveRecord */
		$model = new $this->modelClass();
		$this->setModelAttributes($model, $this->forSaveData);
		$model->save(false);
		$imageSrc = $this->forSaveData['cover_img_src'];
		$this->tester->seeRecord(Image::class, ['file_src' => $imageSrc]);
		$image = $this->tester->grabRecord(Image::class, ['file_src' => $imageSrc]);
		$savedData = array_merge(['cover_img_id' => $image['id']], $this->forSaveData);
		unset($savedData['cover_img_src']);
		$this->tester->seeRecord($this->modelClass, $savedData);
	}

	public function testUpdate() {
		$model = $this->modelClass::findOne($this->id);
		$this->tester->assertNotNull($model);
		$this->setModelAttributes($model, $this->forUpdateData);
		$model->save();
		$updatedData = array_merge(['id' => $this->id], $this->forUpdateData);
		$imageSrc = $updatedData['cover_img_src'];
		unset($updatedData['cover_img_src']);
		$this->tester->seeRecord(Image::class, ['file_src' => $imageSrc]);
		$image = $this->tester->grabRecord(Image::class, ['file_src' => $imageSrc]);
		$this->tester->dontSeeRecord(Image::class, ['file_src' => $this->initData['cover_img_src']]);
		$updatedData['cover_img_id'] = $image['id'];
		$this->tester->seeRecord($this->modelClass, $updatedData);
		unset($this->initData['cover_img_src']);
		$this->tester->dontSeeRecord($this->modelClass, $this->initData);
	}

	public function testDelete() {
		$model = $this->modelClass::findOne($this->id);
		$this->tester->assertNotNull($model);
		$model->delete();
		$deletedData = array_merge(['id' => $this->id], $this->initData);
		$this->tester->dontSeeRecord(Image::class, ['file_src' => $deletedData['cover_img_src']]);
		unset($deletedData['cover_img_src']);
		$this->tester->dontSeeRecord($this->modelClass, $deletedData);
	}

	protected function setModelAttributes(ActiveRecord $model, $data) {
		foreach ($data as $attribute => $value) {
			$model->$attribute = $value;
		}
	}
}
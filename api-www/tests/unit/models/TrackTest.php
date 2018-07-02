<?php

namespace tests\models;

use app\modules\v1\behaviors\FileActiveRecordBehavior;
use app\modules\v1\models\Audiofile;
use app\modules\v1\models\Image;
use app\modules\v1\models\Track;
use yii\db\ActiveRecord;

/**
 * Class AlbumTest
 * @package tests\models
 *
 * @property \UnitTester $tester
 * @property ActiveRecord $modelClass
 */
class TrackTest extends \Codeception\Test\Unit {
	protected $modelClass = Track::class;
	protected $initData = [
		'name' => 'track',
		'audio_file_src' => 'http://ol9.mp3party.net/online/8570/8570866.mp3',
		'album_number' => 2,
	];
	protected $forSaveData = [
		'name' => 'new track',
		'audio_file_src' => 'save file src',
		'album_number' => 2,
	];
	protected $forUpdateData = [
		'name' => 'update track',
		'audio_file_src' => 'update file src',
		'album_number' => 2,
	];

	protected $id;

	function _before() {
		// preparing a user, inserting user record to database
		$fileId = $this->tester->haveRecord(Audiofile::class, ['file_src' => $this->initData['audio_file_src']]);
		// preparing a user, inserting user record to database
		$initData = $this->initData;
		unset($initData['audio_file_src']);
		$initData['file_id'] = $fileId;
		$this->id = $this->tester->haveRecord($this->modelClass, $initData);
	}

	public function testValidation() {
		/* @var $model Track */
		$model = new $this->modelClass;

		$model->name = null;
		$this->tester->assertFalse($model->validate('name'));

		$model->name = 'track';
		$this->tester->assertFalse($model->validate('name'));

		$model->name = 'test';
		$this->tester->assertTrue($model->validate('name'));

		$model->album_id = 'test';
		$this->tester->assertFalse($model->validate('album_id'));

		$model->audio_file_src = 'test';
		$this->tester->assertTrue($model->validate('audio_file_src'));

		$model->setScenario(FileActiveRecordBehavior::SCENARIO_CHECK_FILE_EXIST);
		$model->audio_file_src = 'test';
		$this->tester->assertFalse($model->validate('audio_file_src'));

		$model->audio_file_src = 'http://ol9.mp3party.net/online/8570/8570866.mp3';
		$this->tester->assertTrue($model->validate('audio_file_src'));

		$model->album_number = 'test';
		$this->tester->assertFalse($model->validate('album_number'));

		$model->album_number = 2;
		$this->tester->assertTrue($model->validate('album_number'));
	}

	/**
	 * @depends testValidation
	 */
	public function testCreate() {
		/* @var $model ActiveRecord */
		$model = new $this->modelClass();
		$this->setModelAttributes($model, $this->forSaveData);
		$model->save(false);
		$fileSrc = $this->forSaveData['audio_file_src'];
		$this->tester->seeRecord(Audiofile::class, ['file_src' => $fileSrc]);
		$file = $this->tester->grabRecord(Audiofile::class, ['file_src' => $fileSrc]);
		$savedData = array_merge(['file_id' => $file['id']], $this->forSaveData);
		unset($savedData['audio_file_src']);
		$this->tester->seeRecord($this->modelClass, $savedData);
	}

	public function testUpdate() {
		$model = $this->modelClass::findOne($this->id);
		$this->tester->assertNotNull($model);
		$this->setModelAttributes($model, $this->forUpdateData);
		$model->save();
		$updatedData = array_merge(['id' => $this->id], $this->forUpdateData);
		$fileSrc = $updatedData['audio_file_src'];
		unset($updatedData['audio_file_src']);
		$this->tester->seeRecord(Audiofile::class, ['file_src' => $fileSrc]);
		$file = $this->tester->grabRecord(Audiofile::class, ['file_src' => $fileSrc]);
		$this->tester->dontSeeRecord(Audiofile::class, ['file_src' => $this->initData['audio_file_src']]);
		$updatedData['file_id'] = $file['id'];
		$this->tester->seeRecord($this->modelClass, $updatedData);
		$initData = $this->initData;
		unset($initData['audio_file_src']);
		$this->tester->dontSeeRecord($this->modelClass, $initData);
	}

	public function testDelete() {
		$model = $this->modelClass::findOne($this->id);
		$this->tester->assertNotNull($model);
		$model->delete();
		$deletedData = array_merge(['id' => $this->id], $this->forUpdateData);
		$this->tester->dontSeeRecord(Audiofile::class, ['file_src' => $deletedData['audio_file_src']]);
		unset($deletedData['audio_file_src']);
		$this->tester->dontSeeRecord($this->modelClass, $deletedData);
	}

	protected function setModelAttributes(ActiveRecord $model, $data) {
		foreach ($data as $attribute => $value) {
			$model->$attribute = $value;
		}
	}
}
<?php

namespace app\modules\v1\behaviors;

use app\modules\v1\helpers\FileHelper;
use app\modules\v1\interfaces\FileInterface;
use app\modules\v1\models\Image;
use app\modules\v1\validators\ExistFileValidator;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\validators\Validator;
use Yii;

/**
 * Class FileBehavior
 * @package app\modules\v1\behaviors
 */
class FileActiveRecordBehavior extends Behavior {
	public $fileSrcAttribute = 'file_src';
	public $fileIdAttribute = 'file_id';
	public $fileClass = Image::class;
	public $fileClassRelativeAttribute = 'id';

	/**
	 * @var \yii\validators\Validator[]
	 */
	protected $validators = []; // track references of appended validators

	/**
	 * Get extra validation rules for file
	 *
	 * @return array
	 */
	protected function getFileValidationRules() {
		return [
			[$this->fileSrcAttribute, 'safe'],
			[$this->fileSrcAttribute, 'string'],
			[$this->fileSrcAttribute, ExistFileValidator::class],
			[
				$this->fileIdAttribute,
				'exist',
				'skipOnError' => true,
				'targetClass' => $this->fileClass,
				'targetAttribute' => [$this->fileIdAttribute => $this->fileClassRelativeAttribute]
			],
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @throws InvalidConfigException
	 */
	public function attach($owner) {
		parent::attach($owner);

		if (!isset(class_implements($this->fileClass)[FileInterface::class])) {
			throw new InvalidConfigException($this->fileClass.' must implement '.FileInterface::class);
		}

		if (!is_subclass_of($this->fileClass, ActiveRecord::class)) {
			throw new InvalidConfigException($this->fileClass.' must extends from ActiveRecord');
		}

		$validators = $owner->validators;

		foreach ($this->getFileValidationRules() as $rule) {
			if ($rule instanceof Validator) {
				$validators->append($rule);
				$this->validators[] = $rule; // keep a reference in behavior
			} elseif (is_array($rule) && isset($rule[0], $rule[1])) {
				$validator = Validator::createValidator($rule[1], $owner, $rule[0], array_slice($rule, 2));
				$validators->append($validator);
				$this->validators[] = $validator; // keep a reference in behavior
			} else {
				throw new InvalidConfigException('Invalid validation rule: a rule must specify both attribute names and validator type.');
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function detach() {
		$ownerValidators = $this->owner->validators;
		$cleanValidators = [];
		foreach ($ownerValidators as $validator) {
			if (!in_array($validator, $this->validators)) {
				$cleanValidators[] = $validator;
			}
		}
		$ownerValidators->exchangeArray($cleanValidators);
	}

	/**
	 * Check is file exist
	 */
	protected function existFile() {
		if (!FileHelper::isExistFromSrc($this->{$this->fileSrcAttribute})) {
			$this->owner->addError($this->fileSrcAttribute, 'Invalid "'.$this->fileSrcAttribute.'|. File not found.');
		}
	}

	/**
	 * @inheritdoc
	 */
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdateOrInsert',
			ActiveRecord::EVENT_BEFORE_INSERT => 'beforeUpdateOrInsert',
			ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
		];
	}

	/**
	 * Before insert or update handle function
	 *
	 * @param $event
	 *
	 * @return bool
	 * @throws \Throwable
	 */
	public function beforeUpdateOrInsert($event) {
		/* @var $owner ActiveRecord */
		$owner = $event->sender;
		$insert = $owner->isNewRecord;

		if (!empty($this->getFileSrc($owner))) {
			$transaction = Yii::$app->db->beginTransaction();

			try {
				/* @var $fileClass FileInterface */
				/* @var $file ActiveRecord */
				$fileClass = $this->fileClass;
				$file = $fileClass::initializeFromSrc($this->getFileSrc($owner));

				if ($file->isNewRecord && !$file->save()) {
					$owner->addError($this->fileSrcAttribute, 'Error during save or validate file');

					return false;
				}

				if (!$insert && $this->getFileId($owner) !== $file->id) {
					$file::deleteAll(['id' => $this->getFileId($owner)]);
				}

				$owner->{$this->fileIdAttribute} = $file->id;

				$transaction->commit();
			} catch (\Exception $e) {
				$transaction->rollBack();
				throw $e;
			} catch (\Throwable $e) {
				$transaction->rollBack();
				throw $e;
			}
		}

		return true;
	}

	/**
	 * Before delete handle function
	 *
	 * @param $event
	 *
	 * @return bool
	 * @throws \Throwable
	 */
	public function beforeDelete($event) {
		/* @var $owner ActiveRecord */
		$owner = $event->sender;

		if (!empty($this->getFileId($owner))) {
			Image::deleteAll(['id' => $this->getFileId($owner)]);
		}

		return true;
	}

	/**
	 * Get file model
	 *
	 * @return mixed
	 */
	public function getFile() {
		return $this->owner->hasOne($this->fileClass, [$this->fileClassRelativeAttribute => $this->fileIdAttribute]);
	}

	/**
	 * Get file src from owner model
	 *
	 * @param ActiveRecord $owner
	 *
	 * @return mixed
	 */
	protected function getFileSrc(ActiveRecord $owner) {
		return $owner->{$this->fileSrcAttribute};
	}

	/**
	 * Get file id from owner model
	 *
	 * @param ActiveRecord $owner
	 *
	 * @return mixed
	 */
	protected function getFileId(ActiveRecord $owner) {
		return $owner->{$this->fileIdAttribute};
	}
}
<?php

namespace app\models;

use hiqdev\hiart\ActiveRecord;
use app\behaviors\UploadBehavior;
use yii\helpers\Url;

/**
 * Class Album
 * @package app\models
 *
 * @property int $id
 * @property int $genre_id
 * @property array $genre
 * @property string $name
 * @property array $cover_img
 * @property string $cover_img_src
 * @property int $year
 * @property string $records_name
 */
class Album extends ActiveRecord
{
	public $cover_img;
	public $genre;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['id', 'integer', 'min' => 1],
			[['name', 'year', 'records_name'], 'required'],
			['year', 'integer', 'max' => date('Y')],
			[['genre_id', 'year'], 'integer'],
			[['name', 'records_name'], 'string', 'max' => 255],
			['cover_img_src', 'file'],
		];
	}

	public function afterFind() {
		parent::afterFind();

		if (!empty($this->genre)) {
			$this->setOldAttribute('genre_id', $this->genre['id']);
			$this->genre_id = $this->genre['id'];
		}

		if (!empty($this->cover_img)) {
			$this->setOldAttribute('cover_img_src', $this->cover_img['file_src']);
			$this->cover_img_src = $this->cover_img['file_src'];
		}
	}

	public function beforeSave($insert) {
		if (!parent::beforeSave($insert)) {
			return false;
		}
/*
		if (!empty($this->cover_img_src)) {
			$this->cover_img_src = str_replace(':8080', '', Url::to('@web/upload/images/'.$this->cover_img_src, true));
		}*/

		return true;
	}

	/**
	 * @inheritdoc
	 */
	function behaviors()
	{
		return [
			[
				'class' => UploadBehavior::class,
				'attribute' => 'cover_img_src',
				'scenarios' => [ActiveRecord::SCENARIO_DEFAULT],
				'path' => '@webroot/upload/images/',
				'url' => '@web/upload/images/',
			],
		];
	}
}

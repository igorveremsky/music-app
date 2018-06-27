<?php

namespace app\modules\v1\models;

use app\modules\v1\interfaces\FileInterface;
use Yii;

/**
 * This is the model class for table "{{%images}}".
 *
 * @property int $id
 * @property string $file_src
 */
class Image extends \yii\db\ActiveRecord implements FileInterface
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return '{{%images}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['file_src'], 'required'],
			[['file_src'], 'string', 'max' => 255],
			[['file_src'], 'unique'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function initializeFromSrc(string $src) {
		if (($model = self::find()->where(['file_src' => $src])->limit(1)->one()) === null) {
			$model = new self();
			$model->file_src = $src;
		}

		return $model;
	}
}

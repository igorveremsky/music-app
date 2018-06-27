<?php

namespace app\modules\v1\validators;

use app\modules\v1\helpers\FileHelper;
use Yii;
use yii\validators\Validator;

class ExistFileValidator extends Validator {
	/**
	 * @inheritdoc
	 */
	public function validateAttribute($model, $attribute) {
		if (!FileHelper::isExistFromSrc($model->{$attribute})) {
			$model->addError($attribute, 'Invalid "'.$attribute.'". File not found.');
		}
	}
}
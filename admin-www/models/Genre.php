<?php

namespace app\models;

use hiqdev\hiart\ActiveRecord;

/**
 * Class Genre
 * @package app\models
 *
 * @property int $id
 * @property string $name
 */
class Genre extends ActiveRecord
{
	public function rules()
	{
		return [
			['id', 'integer', 'min' => 1],
			[['name'], 'required'],
			[['name'], 'string', 'max' => 255],
		];
	}
}

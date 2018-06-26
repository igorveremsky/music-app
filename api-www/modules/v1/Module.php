<?php

namespace app\modules\v1;

/**
 * Class Module
 * @package app\modules\v1
 */
class Module extends \yii\base\Module
{
	public $controllerNamespace = 'app\modules\v1\controllers';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		// custom initialization code goes here
	}
}
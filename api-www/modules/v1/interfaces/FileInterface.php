<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\v1\interfaces;

/**
 * Interface FileInterface
 * @package app\modules\v1\interfaces
 */
interface FileInterface {
	/**
	 * Initialize file model from src
	 *
	 * @param string $src
	 *
	 * @return mixed
	 */
	public static function initializeFromSrc(string $src);
}

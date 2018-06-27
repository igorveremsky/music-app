<?php

namespace app\modules\v1\controllers;

use app\modules\v1\models\Favorite;
use Yii;

/**
 * Class FavoriteController
 * @package app\modules\v1\controllers
 */
class FavoriteController extends DefaultController {
	public $modelClass = Favorite::class;
}
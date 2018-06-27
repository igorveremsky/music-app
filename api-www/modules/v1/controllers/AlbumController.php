<?php

namespace app\modules\v1\controllers;

use Yii;
use app\modules\v1\models\Album;

/**
 * Class AlbumController
 * @package app\modules\v1\controllers
 */
class AlbumController extends DefaultController {
	public $modelClass = Album::class;
}
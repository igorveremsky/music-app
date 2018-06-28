<?php

namespace app\controllers;

use Yii;
use app\models\Album;

/**
 * AlbumController implements the CRUD actions for Album model.
 */
class AlbumController extends ActiveController {
	public $modelClass = Album::class;
}
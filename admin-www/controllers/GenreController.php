<?php

namespace app\controllers;

use Yii;
use app\models\Genre;

/**
 * GenreController implements the CRUD actions for Genre model.
 */
class GenreController extends ActiveController {
	public $modelClass = Genre::class;
}
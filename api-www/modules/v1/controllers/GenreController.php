<?php

namespace app\modules\v1\controllers;

use app\modules\v1\models\Genre;

/**
 * Class DefaultController
 * @package app\modules\v1\controllers
 */
class GenreController extends DefaultController {
	public $modelClass = Genre::class;
}
<?php

namespace app\controllers;

use Yii;
use app\models\Artist;

/**
 * ArtistController implements the CRUD actions for Album model.
 */
class ArtistController extends ActiveController {
	public $modelClass = Artist::class;
}
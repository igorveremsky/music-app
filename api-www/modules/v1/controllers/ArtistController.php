<?php

namespace app\modules\v1\controllers;

use app\modules\v1\models\Artist;
use Yii;

/**
 * Class ArtistController
 * @package app\modules\v1\controllers
 */
class ArtistController extends DefaultController {
	public $modelClass = Artist::class;
}
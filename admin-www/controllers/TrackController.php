<?php

namespace app\controllers;

use Yii;
use app\models\Track;

/**
 * TrackController implements the CRUD actions for Track model.
 */
class TrackController extends ActiveController {
	public $modelClass = Track::class;
}
